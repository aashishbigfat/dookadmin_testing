<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Lead;
use Carbon\Carbon;

class MetaLeadController extends Controller
{
    // Platform source mappings - Only FB and Instagram
    private const PLATFORM_SOURCES = [
        'facebook' => [
            'source' => 'facebook',
            'source_medium' => 'fb_lead_ad'
        ],
        'instagram' => [
            'source' => 'instagram', 
            'source_medium' => 'ig_lead_ad'
        ]
    ];

    /**
     * REAL-TIME WEBHOOK HANDLER - Primary method for instant lead capture
     */
    public function receiveLead(Request $request)
    {
        // Webhook verification
        if ($request->has('hub_mode') && $request->hub_mode === 'subscribe') {
            if ($request->hub_verify_token === env('META_VERIFY_TOKEN')) {
                Log::info('Webhook verification successful');
                return response($request->hub_challenge, 200);
            }
            Log::error('Invalid webhook verify token');
            return response('Invalid verify token', 403);
        }

        $body = $request->all();
        Log::info('Meta Webhook Received:', $body);

        if (isset($body['object']) && $body['object'] === 'page') {
            foreach ($body['entry'] as $entry) {
                if (isset($entry['changes'])) {
                    foreach ($entry['changes'] as $change) {
                        if ($change['field'] === 'leadgen') {
                            $this->processWebhookLead($change['value']);
                            return response()->json(['status' => 'lead_processed'], 200);
                        }
                    }
                }
            }
        }

        return response()->json(['status' => 'no_lead_data'], 200);
    }

    /**
     * Process lead from webhook instantly
     */
    private function processWebhookLead($leadValue)
    {
        try {
            $leadId = $leadValue['leadgen_id'];
            
            Log::info('Processing webhook lead:', ['lead_id' => $leadId]);

            // Check if lead already exists to avoid duplicates
            $existingLead = Lead::where('facebook_lead_id', $leadId)->first();
            if ($existingLead) {
                Log::info('Lead already exists, skipping:', ['lead_id' => $leadId]);
                return;
            }

            $token = $this->getWorkingToken();
            if (!$token) {
                Log::error('Could not get access token for webhook processing');
                return;
            }
            
            // Fetch lead details from Facebook API
            $response = Http::timeout(30)->get("https://graph.facebook.com/v23.0/{$leadId}", [
                'access_token' => $token,
                'fields' => 'created_time,field_data,form_id,ad_id,campaign_id'
            ]); 

            if (!$response->successful()) {
                Log::error('Failed to fetch lead details from Facebook:', [
                    'lead_id' => $leadId,
                    'response' => $response->body()
                ]);
                return;
            }

            $leadData = $response->json();
            Log::info('Webhook lead data retrieved:', ['lead_id' => $leadId, 'data' => $leadData]);
            
            // Save and send to CRM immediately
            $this->saveLead($leadId, $leadData);
            
        } catch (\Exception $e) {
            Log::error('Error processing webhook lead:', [
                'lead_id' => $leadValue['leadgen_id'] ?? 'unknown',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * FIXED: Get working access token using App ID and App Secret ONLY
     * This method uses only App tokens which don't expire
     */
    private function getWorkingToken()
    {
        $appId = env('META_APP_ID');
        $appSecret = env('META_APP_SECRET');
        
        if (!$appId || !$appSecret) {
            Log::error('META_APP_ID or META_APP_SECRET not set in environment');
            return null;
        }

        try {
            // Use cached token if available (cache for 50 minutes)
            $cacheKey = 'app_access_token_' . $appId;
            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            // Generate fresh App Access Token
            $response = Http::timeout(30)->get("https://graph.facebook.com/oauth/access_token", [
                'client_id' => $appId,
                'client_secret' => $appSecret,
                'grant_type' => 'client_credentials'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $token = $data['access_token'] ?? null;
                
                if ($token) {
                    // Cache the token for 50 minutes
                    Cache::put($cacheKey, $token, 3000);
                    Log::info('App access token generated and cached successfully');
                    return $token;
                }
            }

            Log::error('Failed to generate app access token:', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            return null;

        } catch (\Exception $e) {
            Log::error('Exception generating app access token:', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * FIXED: Import recent leads using App token
     */
    public function importRecentLeads(Request $request)
    {  
        set_time_limit(300); // 5 minutes
        
        try {
            $token = $this->getWorkingToken();
            $limit = $request->get('limit', 50);
            
            if (!$token) {
                return response()->json(['error' => 'Could not get access token'], 500);
            }

            // Instead of fetching forms from page (which requires page permissions),
            // we'll use the webhook approach and stored form IDs or manual form ID input
            $formIds = $this->getKnownFormIds();
            
            if (empty($formIds)) {
                return response()->json([
                    'error' => 'No form IDs available. Please add form IDs manually or set up webhooks.',
                    'suggestion' => 'Use webhooks for real-time processing or provide form IDs manually'
                ], 400);
            }

            $allLeads = [];

            foreach ($formIds as $formId) {
                $formLeads = $this->getRecentLeadsFromForm($formId, "Form_{$formId}", $token, $limit);
                $allLeads = array_merge($allLeads, $formLeads);
                
                if (count($allLeads) >= $limit) {
                    break;
                }
            }

            $importedCount = 0;
            $skippedCount = 0;
            $crmSuccessCount = 0;
            $crmErrorCount = 0;

            foreach (array_slice($allLeads, 0, $limit) as $leadData) { 
                $existingLead = Lead::where('facebook_lead_id', $leadData['facebook_lead_id'])->first();
                
                if (!$existingLead) {
                    $lead = $this->createLeadFromData($leadData);
                    if ($lead) {
                        $importedCount++;
                        
                        try {
                            $campaignNameToUse = $leadData['ad_name'] ?? $leadData['form_name'];
                            $this->sendToTutterflyyCRM($lead, $leadData['field_data'], $campaignNameToUse);
                            $crmSuccessCount++;
                        } catch (\Exception $e) {
                            Log::error('CRM send failed:', ['lead_id' => $lead->id, 'error' => $e->getMessage()]);
                            $crmErrorCount++;
                        }
                    }
                } else {
                    $skippedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'total_found' => count($allLeads),
                'imported_new' => $importedCount,
                'already_existed' => $skippedCount,
                'crm_success' => $crmSuccessCount,
                'crm_errors' => $crmErrorCount,
                'message' => "Successfully imported {$importedCount} new leads. CRM: {$crmSuccessCount} success, {$crmErrorCount} errors."
            ]);

        } catch (\Exception $e) {
            Log::error('Error importing recent leads:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to import leads: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get known form IDs from database or configuration
     */
    private function getKnownFormIds()
    {
        // Method 1: Get form IDs from existing leads
        $formIds = Lead::distinct()->whereNotNull('form_id')->pluck('form_id')->toArray();
        
        // Method 2: Add manually configured form IDs from environment
        $manualFormIds = env('META_FORM_IDS', ''); // Comma-separated form IDs
        if ($manualFormIds) {
            $additionalIds = array_map('trim', explode(',', $manualFormIds));
            $formIds = array_unique(array_merge($formIds, $additionalIds));
        }
        
        // Method 3: You can also hardcode known form IDs here
        $hardcodedFormIds = [
            // Add your known form IDs here
            // 'your_form_id_1',
            // 'your_form_id_2',
        ];
        
        $formIds = array_unique(array_merge($formIds, $hardcodedFormIds));
        
        Log::info('Using form IDs for import:', ['form_ids' => $formIds]);
        
        return array_filter($formIds); // Remove empty values
    }

    /**
     * SCHEDULED: Backup lead check (every 15 minutes via cron)
     */
    public function scheduledLeadBackup()
    {
        Log::info('Starting scheduled lead backup check');
        
        try {
            $token = $this->getWorkingToken();
            
            if (!$token) {
                Log::error('Could not get access token for backup import');
                return;
            }

            $formIds = $this->getKnownFormIds();
            $totalProcessed = 0;
            $newLeads = 0;

            // Check each form for leads from last hour
            foreach ($formIds as $formId) {
                $recentLeads = $this->getRecentLeadsFromForm($formId, "Form_{$formId}", $token, 1); // Last 1 hour
                
                foreach ($recentLeads as $leadData) {
                    $totalProcessed++;
                    
                    // Check if lead already exists
                    $existingLead = Lead::where('facebook_lead_id', $leadData['facebook_lead_id'])->first();
                    
                    if (!$existingLead) {
                        // Process the missed lead
                        $lead = $this->createLeadFromData($leadData);
                        if ($lead) {
                            $newLeads++;
                            
                            Log::info('Backup captured missed lead:', [
                                'lead_id' => $leadData['facebook_lead_id'],
                                'form_id' => $formId
                            ]);
                        }
                    }
                }
            }

            Log::info('Scheduled backup completed:', [
                'total_checked' => $totalProcessed,
                'new_leads_found' => $newLeads
            ]);

        } catch (\Exception $e) {
            Log::error('Error in scheduled backup:', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get recent leads from specific form (works with App token for individual leads)
     */
    private function getRecentLeadsFromForm($formId, $formName, $token, $hours = 24)
    {
        $leads = [];
        $cutoffTime = Carbon::now()->subHours($hours);
        
        try {
            // For App tokens, we can't list leads directly from forms
            // But we can access individual leads if we have their IDs
            // This method works better with webhooks providing lead IDs
            
            // Alternative: Use a different approach for backup
            Log::info('Form-based lead fetching requires page permissions. Using webhook-based approach instead.');
            
            return $leads; // Return empty for now, rely on webhooks
            
        } catch (\Exception $e) {
            Log::error('Error getting recent leads from form:', [
                'form_id' => $formId,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Create lead from processed data array
     */
    private function createLeadFromData($leadData)
    {
        try {
            // Extract destination
            $destination = $this->extractDestinationFromFieldData($leadData['field_data']);
            if (!$destination) {
                $campaignName = $leadData['ad_name'] ?? $leadData['form_name'];
                if ($campaignName) {
                    $extractedDest = $this->extractDestinationFromName($campaignName);
                    if ($extractedDest !== 'Unknown' && $extractedDest !== $campaignName) {
                        $destination = $extractedDest;
                    }
                }
            }

            // Detect platform
            $mockLead = (object) [
                'facebook_lead_id' => $leadData['facebook_lead_id'],
                'form_id' => $leadData['form_id'],
                'ad_id' => $leadData['ad_id'] ?? null,
                'campaign_id' => $leadData['campaign_id'] ?? null,
            ];
            
            $detectedSource = $this->detectPlatform($mockLead, $leadData['ad_name'] ?? $leadData['form_name']);

            $lead = Lead::create([
                'facebook_lead_id' => $leadData['facebook_lead_id'],
                'form_id' => $leadData['form_id'],
                'ad_id' => $leadData['ad_id'] ?? null,
                'campaign_id' => $leadData['campaign_id'] ?? null,
                'page_id' => env('META_PAGE_ID'),
                'field_data' => $leadData['field_data'],
                'name' => $leadData['name'],
                'email' => $leadData['email'],
                'phone' => $leadData['phone'],
                'destination' => $destination,
                'source' => $detectedSource,
                'lead_created_time' => Carbon::parse($leadData['created_time']),
                'status' => 'new'
            ]);
            
            // Send to CRM automatically
            try {
                $campaignNameToUse = $leadData['ad_name'] ?? $leadData['form_name'];
                $this->sendToTutterflyyCRM($lead, $leadData['field_data'], $campaignNameToUse);
            } catch (\Exception $e) {
                Log::error('Auto CRM send failed:', [
                    'lead_id' => $lead->id,
                    'error' => $e->getMessage()
                ]);
            }
            
            return $lead;
            
        } catch (\Exception $e) {
            Log::error('Error creating lead from data:', [
                'lead_id' => $leadData['facebook_lead_id'],
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * WEBHOOK SETUP METHOD - Subscribe to receive real-time notifications
     */
    public function setupWebhook()
    {
        try {
            $appId = env('META_APP_ID');
            $callbackUrl = url('/api/meta/webhook');
            $verifyToken = env('META_VERIFY_TOKEN');
            
            return response()->json([
                'setup_instructions' => [
                    '1. Go to Facebook Developers Console',
                    '2. Navigate to your app > Webhooks',
                    '3. Click "Add webhook"',
                    '4. Use these settings:',
                    'Callback URL: ' . $callbackUrl,
                    'Verify Token: ' . $verifyToken,
                    'Fields: leadgen',
                    '5. Save and test the webhook'
                ],
                'webhook_url' => $callbackUrl,
                'verify_token' => $verifyToken,
                'app_id' => $appId,
                'note' => 'Webhooks provide instant notifications when forms are submitted. This is the recommended approach for real-time lead capture.'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Test webhook endpoint
     */
    public function testWebhook(Request $request)
    {
        Log::info('Webhook test received:', $request->all());
        
        return response()->json([
            'status' => 'webhook_test_successful',
            'timestamp' => now(),
            'data_received' => $request->all()
        ]);
    }

    /**
     * Add form ID manually for backup processing
     */
    public function addFormId(Request $request)
    {
        $formId = $request->form_id;
        
        if (!$formId) {
            return response()->json(['error' => 'Form ID is required'], 400);
        }

        // Test the form ID with the API
        $token = $this->getWorkingToken();
        
        try {
            $response = Http::timeout(30)->get("https://graph.facebook.com/v23.0/{$formId}", [
                'access_token' => $token,
                'fields' => 'id,name'
            ]);

            if ($response->successful()) {
                $formData = $response->json();
                
                // Add to environment or database for future use
                // You can store this in a forms table or update .env
                
                return response()->json([
                    'success' => true,
                    'form_info' => $formData,
                    'message' => 'Form ID validated and can be used for lead import'
                ]);
            } else {
                return response()->json([
                    'error' => 'Invalid form ID or insufficient permissions',
                    'response' => $response->body()
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Keep all your existing methods below this line...
    // (getAdNameFromId, getCampaignNameFromId, detectPlatform, sendToTutterflyyCRM, etc.)

    /**
     * Get ad name from ad ID
     */
    private function getAdNameFromId($adId, $token)
    {
        if (!$adId) {
            return null;
        }
        
        try {
            $response = Http::timeout(20)->get("https://graph.facebook.com/v23.0/{$adId}", [
                'access_token' => $token,
                'fields' => 'id,name,status,campaign{id,name}'
            ]);

            if ($response->successful()) {
                $adData = $response->json();
                
                Log::info('Retrieved ad details:', [
                    'ad_id' => $adId,
                    'ad_name' => $adData['name'],
                    'ad_status' => $adData['status'] ?? 'unknown'
                ]);
                
                return [
                    'ad_name' => $adData['name'] ?? null,
                    'campaign_id' => $adData['campaign']['id'] ?? null,
                    'campaign_name' => $adData['campaign']['name'] ?? null
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error getting ad details:', [
                'ad_id' => $adId,
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }

    /**
     * Get campaign name directly from campaign ID
     */
    private function getCampaignNameFromId($campaignId, $token)
    {
        if (!$campaignId) {
            return null;
        }
        
        try {
            $response = Http::timeout(20)->get("https://graph.facebook.com/v23.0/{$campaignId}", [
                'access_token' => $token,
                'fields' => 'id,name,objective,status'
            ]);

            if ($response->successful()) {
                $campaignData = $response->json();
                
                Log::info('Retrieved CAMPAIGN from ID:', [
                    'campaign_id' => $campaignId,
                    'campaign_name' => $campaignData['name'],
                    'objective' => $campaignData['objective'] ?? 'unknown'
                ]);
                
                return $campaignData['name'] ?? null;
            } else {
                Log::warning('Failed to get campaign name from ID:', [
                    'campaign_id' => $campaignId,
                    'response' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error getting campaign name from ID:', [
                'campaign_id' => $campaignId,
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }

    /**
     * Extract destination name from campaign name
     */
    private function extractDestinationFromName($campaignName)
    {
        // First, look for text in parentheses - this is the main destination
        if (preg_match('/\(([^)]+)\)/', $campaignName, $matches)) {
            return trim($matches[1]);
        }
        
        // Look for text before | separator
        if (strpos($campaignName, '|') !== false) {
            $parts = explode('|', $campaignName);
            return trim($parts[0]);
        }
        
        // Look for text before - separator
        if (strpos($campaignName, '-') !== false) {
            $parts = explode('-', $campaignName);
            return trim($parts[0]);
        }
        
        // If no separators found, return the campaign name itself
        return $campaignName;
    }

    /**
     * Extract destination from field_data specifically
     */
    private function extractDestinationFromFieldData($fieldData)
    {
        if (!is_array($fieldData)) {
            return null;
        }
        
        // Look for city field first (highest priority)
        foreach ($fieldData as $field) {
            if (isset($field['name']) && strtolower($field['name']) === 'city' && isset($field['values'][0])) {
                return trim($field['values'][0]);
            }
        }
        
        // Look for location-related fields
        foreach ($fieldData as $field) {
            if (isset($field['name']) && isset($field['values'][0])) {
                $fieldName = strtolower($field['name']);
                if (strpos($fieldName, 'location') !== false || 
                    strpos($fieldName, 'destination') !== false || 
                    strpos($fieldName, 'place') !== false ||
                    strpos($fieldName, 'where') !== false) {
                    return trim($field['values'][0]);
                }
            }
        }
        
        return null;
    }

    /**
     * Detect platform - Only FB and Instagram
     */
    private function detectPlatform($lead, $campaignName = null, $excelPlatform = null)
    {
        // Method 1: Use Excel platform field (highest priority) - Only fb/ig
        if ($excelPlatform) {
            $platform = strtolower(trim($excelPlatform));
            
            if ($platform === 'fb' || $platform === 'facebook') {
                Log::info('Platform detected from Excel: Facebook', [
                    'excel_value' => $excelPlatform,
                    'lead_id' => $lead->id ?? 'unknown'
                ]);
                return 'facebook';
            }
                
            if ($platform === 'ig' || $platform === 'instagram' || $platform === 'insta') {
                Log::info('Platform detected from Excel: Instagram', [
                    'excel_value' => $excelPlatform,
                    'lead_id' => $lead->id ?? 'unknown'
                ]);
                return 'instagram';
            }
            
            // Any other value defaults to Facebook
            Log::warning('Unknown platform in Excel, defaulting to Facebook:', [
                'excel_value' => $excelPlatform,
                'lead_id' => $lead->id ?? 'unknown'
            ]);
            return 'facebook';
        }

        // Method 2: Check Facebook-specific IDs (always Facebook)
        if (isset($lead->facebook_lead_id) && $lead->facebook_lead_id) {
            return 'facebook';
        }
        if (isset($lead->form_id) && $lead->form_id) {
            return 'facebook';
        }

        // Method 3: Check campaign name for Instagram indicators only
        if ($campaignName) {
            $campaignLower = strtolower($campaignName);
            
            if (strpos($campaignLower, 'instagram') !== false || 
                strpos($campaignLower, 'ig_') !== false ||
                strpos($campaignLower, 'insta') !== false) {
                return 'instagram';
            }
        }

        // Default fallback - always Facebook
        return 'facebook';
    }

    /**
     * UPDATED: Save lead with destination and source in database
     */
    private function saveLead($leadId, $leadDetails, $excelPlatform = null)
    {
        try {
            // Check for duplicate first
            $existingLead = Lead::where('facebook_lead_id', $leadId)->first();
            if ($existingLead) {
                Log::info('Lead already exists, skipping save:', ['lead_id' => $leadId]);
                return true;
            }

            $fieldData = $leadDetails['field_data'] ?? [];
            
            $name = $this->extractFieldValue($fieldData, ['full_name', 'name', 'first_name']);
            $email = $this->extractFieldValue($fieldData, ['email']);
            $phone = $this->extractFieldValue($fieldData, ['phone_number', 'phone', 'mobile']);

            // Get campaign/ad information
            $token = $this->getWorkingToken();
            $adName = null;
            $campaignName = null;
            
            if (isset($leadDetails['ad_id']) && $leadDetails['ad_id']) {
                $adDetails = $this->getAdNameFromId($leadDetails['ad_id'], $token);
                if ($adDetails) {
                    $adName = $adDetails['ad_name'];
                    $campaignName = $adDetails['campaign_name'] ?? $adName;
                }
            }
            
            if (!$campaignName && isset($leadDetails['campaign_id'])) {
                $campaignName = $this->getCampaignNameFromId($leadDetails['campaign_id'], $token);
            }

            // Extract destination from field_data or campaign name
            $destination = $this->extractDestinationFromFieldData($fieldData);
            if (!$destination && $campaignName) {
                $destination = $this->extractDestinationFromName($campaignName);
                if ($destination === 'Unknown' || $destination === $campaignName) {
                    $destination = null;
                }
            }

            // Create mock lead object for platform detection
            $mockLead = (object) [
                'facebook_lead_id' => $leadId,
                'form_id' => $leadDetails['form_id'] ?? null,
                'ad_id' => $leadDetails['ad_id'] ?? null,
                'campaign_id' => $leadDetails['campaign_id'] ?? null,
            ];

            // Detect platform/source
            $detectedPlatform = $this->detectPlatform($mockLead, $campaignName, $excelPlatform);

            $lead = Lead::create([
                'facebook_lead_id' => $leadId,
                'form_id' => $leadDetails['form_id'] ?? null,
                'ad_id' => $leadDetails['ad_id'] ?? null,
                'campaign_id' => $leadDetails['campaign_id'] ?? null,
                'page_id' => env('META_PAGE_ID'),
                'field_data' => $fieldData,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'destination' => $destination, // Save destination
                'source' => $detectedPlatform, // Save source (facebook/instagram)
                'lead_created_time' => isset($leadDetails['created_time']) 
                    ? Carbon::parse($leadDetails['created_time']) 
                    : now(),
                'status' => 'new'
            ]);

            Log::info('Lead saved successfully:', [
                'lead_id' => $leadId,
                'database_id' => $lead->id,
                'destination' => $destination,
                'source' => $detectedPlatform
            ]);

            // Send to TutterflyyCRM after saving
            $finalCampaignName = $campaignName ?: ($adName ?: 'Unknown Campaign');
            $this->sendToTutterflyyCRM($lead, $fieldData, $finalCampaignName, $excelPlatform);

            return true;
        } catch (\Exception $e) {
            Log::error('Error saving lead:', ['lead_id' => $leadId, 'error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * UPDATED: Send lead data to TutterflyyCRM API with stored destination/source
     */
    private function sendToTutterflyyCRM($lead, $fieldData, $campaignName = null, $excelPlatform = null)
    {
        try {
            // Extract additional fields from lead data
            $city = $this->extractFieldValue($fieldData, ['city', 'location']);
            $numberOfPeople = $this->extractFieldValue($fieldData, ['number_of_people_travelling?', 'travelers', 'pax']);
            $travelMonth = $this->extractFieldValue($fieldData, ['when_are_you_planning_to_travel?', 'travel_date', 'departure_date']);
            
            $finalCampaignName = $campaignName;
            $token = $this->getWorkingToken();
            
            // Get campaign name with priority order
            if ($lead->campaign_id) {
                $campaignNameFromId = $this->getCampaignNameFromId($lead->campaign_id, $token);
                if ($campaignNameFromId) {
                    $finalCampaignName = $campaignNameFromId;
                    Log::info('Using campaign name from campaign_id:', [
                        'lead_id' => $lead->id,
                        'campaign_id' => $lead->campaign_id,
                        'campaign_name' => $finalCampaignName
                    ]);
                }
            } elseif ($lead->ad_id) {
                $adDetails = $this->getAdNameFromId($lead->ad_id, $token);
                if ($adDetails && $adDetails['ad_name']) {
                    $finalCampaignName = $adDetails['ad_name'];
                    Log::info('Using ad name as campaign_name:', [
                        'lead_id' => $lead->id,
                        'ad_id' => $lead->ad_id,
                        'ad_name' => $finalCampaignName
                    ]);
                }
            }
            
            // Use stored source or detect platform
            $detectedPlatform = $lead->source ?: $this->detectPlatform($lead, $finalCampaignName, $excelPlatform);
            $platformInfo = $this->getPlatformSourceInfo($detectedPlatform);
            
            // Use stored destination or extract from campaign name
            $destination = $lead->destination ?: $this->extractDestinationFromName($finalCampaignName ?: 'Unknown');
            if (empty($destination) || $destination === 'Unknown') {
                $destination = $city ?: 'India'; // Fallback to city or India
            }
            
            // Convert month name to first date of that month
            $travelDate = $this->convertMonthToDate($travelMonth);
            
            // Prepare data for TutterflyyCRM with DYNAMIC FB/IG source
            $crmData = [
                "token" => env('TUTTERFLY_CRM_TOKEN'),
                "lead" => [
                    "first_name" => $this->extractFirstName($lead->name),
                    "last_name" => $this->extractLastName($lead->name),
                    "city" => $city ?: '',
                    'region' => '', 
                    "country" => 'India',
                    "email" => $lead->email ?: '',
                    "mobile" => $lead->phone ?: '',
                    "phone" => $lead->phone ?: '',
                    "company" => '',
                    "no_of_nights" => '',
                    "url" => request()->headers->get('referer', ''),
                    "destination_json" => json_encode(['destination' => $destination]),
                    "destinations_name" => $destination,
                    "min_country_data" => '',
                    "fixed_departure" => '',
                    "form_type" => $detectedPlatform . '_lead_ad', // facebook_lead_ad or instagram_lead_ad
                    "experience" => '',
                    "website" => $this->getPlatformWebsite($detectedPlatform),
                    "campaign_name" => $finalCampaignName ?: $lead->form_id,
                    "source" => $platformInfo['source'], // facebook or instagram
                    "source_medium" => $detectedPlatform . '_lead_ad', // fb_lead_ad or ig_lead_ad
                    "ref_id" => $lead->facebook_lead_id ?: $lead->id,
                    "no_of_pax" => $numberOfPeople ?: '1',
                    "ip" => $this->getClientIP(),
                    "travel_date" => $travelDate,
                    "dook_enquiry_id" => $lead->id,
                    "departure_id" => "",
                    "custom_fields" => [
                        "segment" => "",
                        "destination" => $destination,
                        "description" => $this->buildLeadDescription($fieldData),
                        "no_of_passengers" => $numberOfPeople ?: '1',
                        "date_of_travel" => $travelDate,
                        "bnpl" => '',
                        "campaign_url" => '',
                        "experience" => '',
                        "platform_detected" => $detectedPlatform, // facebook or instagram
                        "excel_platform" => $excelPlatform, // Original Excel value (fb/ig)
                        "facebook_form_id" => $lead->form_id,
                        "facebook_ad_id" => $lead->ad_id,
                        "facebook_lead_id" => $lead->facebook_lead_id,
                        "facebook_campaign_id" => $lead->campaign_id,
                        "actual_campaign_name" => $finalCampaignName,
                        "original_form_name" => $campaignName,
                        "extracted_destination" => $destination,
                        "name_source" => $lead->campaign_id ? 'campaign_id' : ($lead->ad_id ? 'ad_id' : 'form_name'),
                        "stored_destination" => $lead->destination, // Show what was stored in DB
                        "stored_source" => $lead->source // Show what was stored in DB
                    ]
                ]
            ];
            
            // Log the data being sent for debugging
            Log::info('Sending to TutterflyyCRM with stored destination/source:', [
                'facebook_lead_id' => $lead->facebook_lead_id,
                'stored_destination' => $lead->destination,
                'stored_source' => $lead->source,
                'final_destination' => $destination,
                'detected_platform' => $detectedPlatform,
                'source_medium' => $detectedPlatform . '_lead_ad',
                'campaign_name_used' => $finalCampaignName
            ]);

            // Send to TutterflyyCRM API
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post('https://dooktravels.tutterflycrm.com/tfc/api/capture_lead', $crmData);

            if ($response->successful()) {
                Log::info('Lead sent to TutterflyyCRM successfully', [
                    'facebook_lead_id' => $lead->facebook_lead_id,
                    'platform' => $detectedPlatform,
                    'destination' => $destination,
                    'campaign_name' => $finalCampaignName,
                    'crm_response' => $response->json()
                ]);
                
                $lead->update(['status' => 'sent_to_crm']);
                
            } else {
                Log::error('Failed to send lead to TutterflyyCRM', [
                    'facebook_lead_id' => $lead->facebook_lead_id,
                    'platform' => $detectedPlatform,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                
                $lead->update(['status' => 'crm_error']);
            }

        } catch (\Exception $e) {
            Log::error('Error sending lead to TutterflyyCRM:', [
                'facebook_lead_id' => $lead->facebook_lead_id,
                'error' => $e->getMessage()
            ]);
            
            $lead->update(['status' => 'crm_error']);
        }
    }

    /**
     * Get platform-specific source information - Only FB/IG
     */
    private function getPlatformSourceInfo($platform)
    {
        if ($platform === 'instagram') {
            return [
                'source' => 'instagram',
                'source_medium' => 'ig_lead_ad'
            ];
        }
        
        // Default to Facebook for everything else
        return [
            'source' => 'facebook',
            'source_medium' => 'fb_lead_ad'
        ];
    }

    /**
     * Get platform-specific website URL - Only FB/IG
     */
    private function getPlatformWebsite($platform)
    {
        return $platform === 'instagram' ? 'instagram.com' : 'facebook.com';
    }

    /**
     * Convert month name to first date of that month
     */
    private function convertMonthToDate($monthInput)
    {
        if (empty($monthInput)) {
            return date('Y-m-d');
        }
        
        $monthInput = strtolower(trim($monthInput));
        $currentYear = date('Y');
        
        $months = [
            'january' => '01', 'jan' => '01',
            'february' => '02', 'feb' => '02',
            'march' => '03', 'mar' => '03',
            'april' => '04', 'apr' => '04',
            'may' => '05',
            'june' => '06', 'jun' => '06',
            'july' => '07', 'jul' => '07',
            'august' => '08', 'aug' => '08',
            'september' => '09', 'sep' => '09', 'sept' => '09',
            'october' => '10', 'oct' => '10',
            'november' => '11', 'nov' => '11',
            'december' => '12', 'dec' => '12'
        ];
        
        if (isset($months[$monthInput])) {
            $month = $months[$monthInput];
            
            // If the month is in the past, use next year
            if ($month < date('m')) {
                $currentYear++;
            }
            
            return "{$currentYear}-{$month}-01";
        }
        
        // Try to parse other date formats
        try {
            $date = Carbon::parse($monthInput);
            return $date->format('Y-m-d');
        } catch (\Exception $e) {
            return date('Y-m-d');
        }
    }

    /**
     * Extract first name from full name
     */
    private function extractFirstName($fullName)
    {
        if (empty($fullName)) {
            return '';
        }
        
        $nameParts = explode(' ', trim($fullName));
        return $nameParts[0];
    }

    /**
     * Extract last name from full name
     */
    private function extractLastName($fullName)
    {
        if (empty($fullName)) {
            return '';
        }
        
        $nameParts = explode(' ', trim($fullName));
        if (count($nameParts) > 1) {
            array_shift($nameParts);
            return implode(' ', $nameParts);
        }
        
        return '';
    }

    /**
     * Build description from all form fields
     */
    private function buildLeadDescription($fieldData)
    {
        $description = "Lead Ad Response:\n";
        
        if (is_array($fieldData)) {
            foreach ($fieldData as $field) {
                $question = $field['name'] ?? 'Unknown';
                $answer = implode(', ', $field['values'] ?? []);
                $description .= "{$question}: {$answer}\n";
            }
        }
        
        return trim($description);
    }

    /**
     * Get client IP address
     */
    private function getClientIP()
    {
        $ipKeys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ips = explode(',', $_SERVER[$key]);
                return trim($ips[0]);
            }
        }
        
        return '127.0.0.1';
    }

    // Get leads with destination and source filtering
    public function getLeads(Request $request)
    {
        $query = Lead::orderBy('lead_created_time', 'desc');

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('destination', 'like', "%{$search}%");
            });
        }

        // Filter by source
        if ($request->has('source') && $request->source !== 'all') {
            $query->where('source', $request->source);
        }

        // Filter by destination
        if ($request->has('destination') && $request->destination) {
            $query->where('destination', 'like', "%{$request->destination}%");
        }

        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('lead_created_time', '>=', $request->from_date);
        }
        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('lead_created_time', '<=', $request->to_date);
        }

        $perPage = $request->get('per_page', 20);
        $leads = $query->paginate($perPage);

        return response()->json($leads);
    }

    // Update lead status
    public function updateLeadStatus(Request $request, $id)
    {
        try {
            $lead = Lead::findOrFail($id);
            
            $validStatuses = ['new', 'contacted', 'converted', 'lost', 'sent_to_crm', 'crm_error'];
            if (!in_array($request->status, $validStatuses)) {
                return response()->json(['error' => 'Invalid status'], 400);
            }

            $lead->update(['status' => $request->status]);
            
            return response()->json(['success' => true, 'lead' => $lead]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Lead not found'], 404);
        }
    }

    // Dashboard statistics with database-based platform stats
    public function getDashboardStats()
    {
        try {
            $stats = [
                'total_leads' => Lead::count(),
                'new_leads' => Lead::where('status', 'new')->count(),
                'contacted_leads' => Lead::where('status', 'contacted')->count(),
                'converted_leads' => Lead::where('status', 'converted')->count(),
                'lost_leads' => Lead::where('status', 'lost')->count(),
                'sent_to_crm' => Lead::where('status', 'sent_to_crm')->count(),
                'crm_errors' => Lead::where('status', 'crm_error')->count(),
                'today_leads' => Lead::whereDate('lead_created_time', today())->count(),
                'this_week_leads' => Lead::whereBetween('lead_created_time', [
                    now()->startOfWeek(), now()->endOfWeek()
                ])->count(),
                'this_month_leads' => Lead::whereMonth('lead_created_time', now()->month)
                    ->whereYear('lead_created_time', now()->year)->count(),
                // Database-based platform stats
                'platform_stats' => [
                    'facebook' => Lead::where('source', 'facebook')->count(),
                    'instagram' => Lead::where('source', 'instagram')->count()
                ],
                // Destination stats
                'destination_stats' => Lead::whereNotNull('destination')
                    ->groupBy('destination')
                    ->selectRaw('destination, count(*) as count')
                    ->orderBy('count', 'desc')
                    ->take(10)
                    ->get()
                    ->pluck('count', 'destination')
                    ->toArray()
            ];

            return response()->json($stats);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to get statistics'], 500);
        }
    }

    /**
     * Test connection using App token
     */
    public function testConnection()
    {
        try {
            $token = $this->getWorkingToken();
            
            if (!$token) {
                return response()->json(['error' => 'Could not get access token'], 500);
            }

            // Test with app info endpoint (works with app tokens)
            $response = Http::timeout(30)->get("https://graph.facebook.com/v23.0/" . env('META_APP_ID'), [
                'access_token' => $token,
                'fields' => 'id,name'
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json(),
                    'token_type' => 'App Access Token',
                    'message' => 'Successfully connected to Facebook API using App ID/Secret'
                ]);
            } else {
                return response()->json([
                    'error' => 'Failed to connect to Facebook API',
                    'response' => $response->body()
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function extractFieldValue($fieldData, $fieldNames)
    {
        if (!is_array($fieldData)) {
            return null;
        }
        
        foreach ($fieldData as $field) {
            if (isset($field['name']) && in_array(strtolower($field['name']), array_map('strtolower', $fieldNames))) {
                return $field['values'][0] ?? null;
            }
        }
        return null;
    }
}