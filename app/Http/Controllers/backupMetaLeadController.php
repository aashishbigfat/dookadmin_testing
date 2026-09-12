<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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

    // Webhook handler
    public function receiveLead(Request $request)
    {
        if ($request->has('hub_mode') && $request->hub_mode === 'subscribe') {
            if ($request->hub_verify_token === env('META_VERIFY_TOKEN')) {
                return response($request->hub_challenge, 200);
            }
            return response('Invalid verify token', 403);
        }

        $body = $request->all();
        Log::info('Meta Webhook Payload:', $body);

        if (isset($body['object']) && $body['object'] === 'page') {
            foreach ($body['entry'] as $entry) {
                if (isset($entry['changes'])) {
                    foreach ($entry['changes'] as $change) {
                        if ($change['field'] === 'leadgen') {
                            $leadData = $this->processLead($change['value']);
                            return response()->json(['status' => 'lead_received', 'data' => $leadData]);
                        }
                    }
                }
            }
        }

        return response()->json(['status' => 'no_lead'], 200);
    }

    private function processLead($leadValue)
    {
        $leadId = $leadValue['leadgen_id'];
        $token = $this->getWorkingToken();
        
        if (!$token) {
            Log::error('Could not get access token for lead processing');
            return null;
        }
        
        $response = Http::get("https://graph.facebook.com/v23.0/{$leadId}", [
            'access_token' => $token,
            'fields' => 'created_time,field_data,form_id,ad_id,campaign_id'
        ]); 

        $leadData = $response->json();
        Log::info('Lead Data:', $leadData);
        $this->saveLead($leadId, $leadData);
        return $leadData;
    }

    /**
     * Get working access token
     */
    private function getWorkingToken()
    {
        $userToken = env('META_USER_ACCESS_TOKEN');
        
        if (!$userToken) {
            Log::error('META_USER_ACCESS_TOKEN not set in environment');
            return null;
        }

        return $this->convertToPageToken($userToken);
    }

    private function convertToPageToken($userToken)
    {
        try {
            $response = Http::timeout(30)->get("https://graph.facebook.com/v23.0/me/accounts", [
                'access_token' => $userToken,
                'fields' => 'access_token,name,id'
            ]);

            if (!$response->successful()) {
                Log::error('Failed to get page token:', ['response' => $response->body()]);
                return null;
            }

            $accounts = $response->json();
            $pageId = env('META_PAGE_ID');

            foreach ($accounts['data'] as $account) {
                if ($account['id'] === $pageId) {
                    return $account['access_token'];
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Error converting to page token:', ['error' => $e->getMessage()]);
            return null;
        }
    }

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
     * Get campaign name from form ID
     */
    private function getCampaignNameFromForm($formId, $token)
    {
        try {
            $response = Http::get("https://graph.facebook.com/v23.0/{$formId}", [
                'access_token' => $token,
                'fields' => 'name'
            ]);

            if ($response->successful()) {
                $formData = $response->json();
                return $formData['name'] ?? 'Unknown Campaign';
            }
        } catch (\Exception $e) {
            Log::error('Error getting form name:', ['error' => $e->getMessage()]);
        }

        return 'Unknown Campaign';
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

    // Import with immediate processing but limited scope
    public function importRecentLeads(Request $request)
    {  
        set_time_limit(300); // 5 minutes
        
        try {
            $pageId = env('META_PAGE_ID');
            $token = $this->getWorkingToken();
            $limit = $request->get('limit', 50);
            
            if (!$token) {
                return response()->json(['error' => 'Could not get access token'], 500);
            }

            $allLeads = [];
            $formsUrl = "https://graph.facebook.com/v23.0/{$pageId}/leadgen_forms";

            // Get forms
            $formsResponse = Http::timeout(60)->get($formsUrl, [
                'access_token' => $token,
                'fields' => 'id,name',
                'limit' => 10
            ]);

            if (!$formsResponse->successful()) {
                Log::error('Failed to fetch forms:', ['response' => $formsResponse->body()]);
                return response()->json(['error' => 'Failed to fetch forms'], 500);
            }

            $formsData = $formsResponse->json();
         
            foreach ($formsData['data'] as $form) {
                $formLeads = $this->getRecentLeadsFromForm($form['id'], $form['name'], $token, $limit);
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
                        'lead_created_time' => Carbon::parse($leadData['created_time']),
                        'status' => 'new'
                    ]);
                    
                    try {
                        $campaignNameToUse = $leadData['ad_name'] ?? $leadData['form_name'];
                        $this->sendToTutterflyyCRM($lead, $leadData['field_data'], $campaignNameToUse);
                        $crmSuccessCount++;
                    } catch (\Exception $e) {
                        Log::error('CRM send failed:', ['lead_id' => $lead->id, 'error' => $e->getMessage()]);
                        $crmErrorCount++;
                    }
                    
                    $importedCount++;
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

    private function getRecentLeadsFromForm($formId, $formName, $token, $maxLeads = 50)
    {
        $leads = [];
        $leadsUrl = "https://graph.facebook.com/v23.0/{$formId}/leads";

        $response = Http::timeout(60)->get($leadsUrl, [
            'access_token' => $token,
            'fields' => 'id,created_time,field_data,ad_id,campaign_id',
            'limit' => min($maxLeads, 25)
        ]);

        if (!$response->successful()) {
            Log::error('Failed to fetch leads from form:', [
                'form_id' => $formId, 
                'response' => $response->body()
            ]);
            return [];
        }

        $data = $response->json();
        
        foreach ($data['data'] as $lead) {
            $adId = $lead['ad_id'] ?? null;
            $campaignId = $lead['campaign_id'] ?? null;
            $adName = null;
            
            // Get ad name if we have ad_id
            if ($adId) {
                $adDetails = $this->getAdNameFromId($adId, $token);
                if ($adDetails) {
                    $adName = $adDetails['ad_name'];
                }
            }
            
            $leads[] = [
                'facebook_lead_id' => $lead['id'],
                'form_id' => $formId,
                'form_name' => $formName,
                'ad_id' => $adId,
                'campaign_id' => $campaignId,
                'ad_name' => $adName,
                'field_data' => $lead['field_data'],
                'created_time' => $lead['created_time'],
                'name' => $this->extractFieldValue($lead['field_data'], ['full_name', 'name', 'first_name']),
                'email' => $this->extractFieldValue($lead['field_data'], ['email']),
                'phone' => $this->extractFieldValue($lead['field_data'], ['phone_number', 'phone', 'mobile'])
            ];
            
            if (count($leads) >= $maxLeads) {
                break;
            }
        }

        return $leads;
    }

    private function saveLead($leadId, $leadDetails, $excelPlatform = null)
    {
        try {
            $fieldData = $leadDetails['field_data'] ?? [];
            
            $name = $this->extractFieldValue($fieldData, ['full_name', 'name', 'first_name']);
            $email = $this->extractFieldValue($fieldData, ['email']);
            $phone = $this->extractFieldValue($fieldData, ['phone_number', 'phone', 'mobile']);

            // Get ad name if we have ad_id
            $token = $this->getWorkingToken();
            $adName = null;
            if (isset($leadDetails['ad_id']) && $leadDetails['ad_id']) {
                $adDetails = $this->getAdNameFromId($leadDetails['ad_id'], $token);
                if ($adDetails) {
                    $adName = $adDetails['ad_name'];
                }
            }

            $lead = Lead::updateOrCreate(
                ['facebook_lead_id' => $leadId],
                [
                    'form_id' => $leadDetails['form_id'] ?? null,
                    'ad_id' => $leadDetails['ad_id'] ?? null,
                    'campaign_id' => $leadDetails['campaign_id'] ?? null,
                    'page_id' => env('META_PAGE_ID'),
                    'field_data' => $fieldData,
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'destination' => $destination,
                    'source' => $detectedPlatform . '_lead_ad', 
                    'lead_created_time' => isset($leadDetails['created_time']) 
                        ? Carbon::parse($leadDetails['created_time']) 
                        : now(),
                    'status' => 'new'
                ]
            );

            // Send to TutterflyyCRM after saving
            $campaignNameToUse = $adName ?: $this->getCampaignNameFromForm($leadDetails['form_id'], $token);
            $this->sendToTutterflyyCRM($lead, $fieldData, $campaignNameToUse, $excelPlatform);

            return true;
        } catch (\Exception $e) {
            Log::error('Error saving lead:', ['lead_id' => $leadId, 'error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Send lead data to TutterflyyCRM API with FB/IG platform detection
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
            
            // FB/IG PLATFORM DETECTION
            $detectedPlatform = $this->detectPlatform($lead, $finalCampaignName, $excelPlatform);
            $platformInfo = $this->getPlatformSourceInfo($detectedPlatform);
            
            // Extract destination from campaign name
            $destination = $this->extractDestinationFromName($finalCampaignName ?: 'Unknown');
            
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
                    "source_medium" => $detectedPlatform . '_lead_ad', // lead_ad
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
                        "name_source" => $lead->campaign_id ? 'campaign_id' : ($lead->ad_id ? 'ad_id' : 'form_name')
                    ]
                ]
            ];
            
            // Log the data being sent for debugging
            Log::info('Sending to TutterflyyCRM with FB/IG platform:', [
                'facebook_lead_id' => $lead->facebook_lead_id,
                'excel_platform' => $excelPlatform,
                'detected_platform' => $detectedPlatform,
                'source' => $platformInfo['source'],
                'source_medium' => $detectedPlatform . '_lead_ad',
                'campaign_name_used' => $finalCampaignName,
                'destination_extracted' => $destination
            ]);
       
dd($crmData);
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

    // Get leads with filtering
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
                  ->orWhere('phone', 'like', "%{$search}%");
            });
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

    // Dashboard statistics with FB/IG platform stats
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
                // FB/IG platform-specific stats
                'platform_stats' => $this->getPlatformStats(),
            ];

            return response()->json($stats);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to get statistics'], 500);
        }
    }

    /**
     * Get platform stats - Only FB/IG
     */
    private function getPlatformStats()
    {
        try {
            $leads = Lead::whereNotNull('field_data')->get();
            $platformStats = ['facebook' => 0, 'instagram' => 0];
            
            foreach ($leads as $lead) {
                $detectedPlatform = $this->detectPlatform($lead);
                $platformStats[$detectedPlatform]++;
            }
            
            return $platformStats;
        } catch (\Exception $e) {
            Log::error('Error getting platform stats:', ['error' => $e->getMessage()]);
            return ['facebook' => 0, 'instagram' => 0];
        }
    }

    /**
     * Test connection
     */
    public function testConnection()
    {
        try {
            $token = $this->getWorkingToken();
            
            if (!$token) {
                return response()->json(['error' => 'Could not get access token'], 500);
            }

            $response = Http::timeout(30)->get("https://graph.facebook.com/v23.0/me", [
                'access_token' => $token
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json(),
                    'message' => 'Successfully connected to Facebook API'
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

    /**
     * Test campaign name retrieval
     */
    public function testCampaignName($campaignId = null)
    {
        try {
            $token = $this->getWorkingToken();
            
            if (!$token) {
                return response()->json(['error' => 'Could not get access token'], 500);
            }

            $testCampaignId = $campaignId ?: '120229239408110393';
            
            $campaignName = $this->getCampaignNameFromId($testCampaignId, $token);
            
            if ($campaignName) {
                return response()->json([
                    'success' => true,
                    'campaign_id' => $testCampaignId,
                    'campaign_name' => $campaignName,
                    'extracted_destination' => $this->extractDestinationFromName($campaignName),
                    'message' => 'Successfully retrieved campaign name'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'campaign_id' => $testCampaignId,
                    'message' => 'Could not retrieve campaign name'
                ], 404);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Test FB/IG platform detection
     */
    public function testPlatformDetection(Request $request)
    {
        try {
            $mockLead = (object) [
                'id' => 999,
                'facebook_lead_id' => $request->get('facebook_lead_id'),
                'form_id' => $request->get('form_id'),
                'campaign_id' => $request->get('campaign_id'),
                'ad_id' => $request->get('ad_id')
            ];

            $campaignName = $request->get('campaign_name', '');
            $excelPlatform = $request->get('platform', ''); // fb or ig
            
            $detectedPlatform = $this->detectPlatform($mockLead, $campaignName, $excelPlatform);
            $platformInfo = $this->getPlatformSourceInfo($detectedPlatform);
            
            return response()->json([
                'success' => true,
                'platforms_supported' => 'Facebook and Instagram ONLY',
                'detected_platform' => $detectedPlatform,
                'source' => $platformInfo['source'],
                'source_medium' => $detectedPlatform . '_lead_ad',
                'website' => $this->getPlatformWebsite($detectedPlatform),
                'form_type' => $detectedPlatform . '_lead_ad',
                'test_data' => [
                    'campaign_name' => $campaignName,
                    'excel_platform' => $excelPlatform,
                    'has_facebook_lead_id' => !empty($mockLead->facebook_lead_id),
                    'has_form_id' => !empty($mockLead->form_id)
                ],
                'detection_method' => $this->getDetectionMethod($mockLead, $campaignName, $excelPlatform)
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get detection method - Only FB/IG
     */
    private function getDetectionMethod($lead, $campaignName = null, $excelPlatform = null)
    {
        if ($excelPlatform) {
            $platform = strtolower(trim($excelPlatform));
            
            if ($platform === 'fb' || $platform === 'facebook') {
                return "Excel platform field: '{$excelPlatform}' → 'facebook'";
            }
            if ($platform === 'ig' || $platform === 'instagram' || $platform === 'insta') {
                return "Excel platform field: '{$excelPlatform}' → 'instagram'";
            }
            return "Excel platform field: '{$excelPlatform}' → 'facebook' (default)";
        }

        if (isset($lead->facebook_lead_id) && $lead->facebook_lead_id) {
            return 'Facebook-specific lead ID detected';
        }
        if (isset($lead->form_id) && $lead->form_id) {
            return 'Facebook-specific form ID detected';
        }

        if ($campaignName) {
            $campaignLower = strtolower($campaignName);
            if (strpos($campaignLower, 'instagram') !== false || 
                strpos($campaignLower, 'ig_') !== false ||
                strpos($campaignLower, 'insta') !== false) {
                return "Campaign name contains Instagram keywords";
            }
        }

        return 'Default fallback to Facebook';
    }

    /**
     * Import leads from Excel data - FB/IG ONLY
     */
    public function importFromExcel(Request $request)
    {
        try {
            $excelData = $request->input('leads', []);
            
            if (empty($excelData)) {
                return response()->json(['error' => 'No lead data provided'], 400);
            }

            $importedCount = 0;
            $skippedCount = 0;
            $crmSuccessCount = 0;
            $crmErrorCount = 0;
            $fbCount = 0;
            $igCount = 0;

            foreach ($excelData as $excelLead) {
                try {
                    // Validate platform is fb or ig only
                    $platform = strtolower($excelLead['platform'] ?? 'fb');
                    if (!in_array($platform, ['fb', 'facebook', 'ig', 'instagram', 'insta'])) {
                        Log::warning('Skipping lead with unsupported platform:', [
                            'platform' => $platform,
                            'supported' => 'fb, ig only'
                        ]);
                        $skippedCount++;
                        continue;
                    }

                    $leadId = $excelLead['ad_id'] ?? null;
                    $formId = $excelLead['form_id'] ?? null;   
                    $campaignId = $excelLead['campaign_id'] ?? null; 
                    $adId = $excelLead['ad_id'] ?? null;    
                    
                    if (!$leadId) {
                        $skippedCount++;
                        continue;
                    }

                    // Check if lead already exists
                    $existingLead = Lead::where('facebook_lead_id', $leadId)
                                       ->orWhere(function($query) use ($excelLead) {
                                           $query->where('phone', $excelLead['phone_number'] ?? '')
                                                ->where('name', $excelLead['full_name'] ?? '');
                                       })
                                       ->first();
                    
                    if ($existingLead) {
                        $skippedCount++;
                        continue;
                    }

                    // Create field data array from Excel data
                    $fieldData = [];
                    
                    if (isset($excelLead['number_of_people_travelling?'])) {
                        $fieldData[] = [
                            'name' => 'number_of_people_travelling?',
                            'values' => [$excelLead['number_of_people_travelling?']]
                        ];
                    }
                    
                    if (isset($excelLead['when_are_you_planning_to_travel?'])) {
                        $fieldData[] = [
                            'name' => 'when_are_you_planning_to_travel?',
                            'values' => [$excelLead['when_are_you_planning_to_travel?']]
                        ];
                    }
                    
                    if (isset($excelLead['full_name'])) {
                        $fieldData[] = [
                            'name' => 'full_name',
                            'values' => [$excelLead['full_name']]
                        ];
                    }
                    
                    if (isset($excelLead['phone_number'])) {
                        $fieldData[] = [
                            'name' => 'phone_number',
                            'values' => [$excelLead['phone_number']]
                        ];
                    }
                    
                    if (isset($excelLead['city'])) {
                        $fieldData[] = [
                            'name' => 'city',
                            'values' => [$excelLead['city']]
                        ];
                    }

                    // Create the lead
                    $lead = Lead::create([
                        'facebook_lead_id' => $leadId,
                        'form_id' => $formId,
                        'ad_id' => $adId,
                        'campaign_id' => $campaignId,
                        'page_id' => env('META_PAGE_ID'),
                        'field_data' => $fieldData,
                        'name' => $excelLead['full_name'] ?? '',
                        'email' => $excelLead['email'] ?? '',
                        'phone' => $excelLead['phone_number'] ?? '',
                        'lead_created_time' => now(),
                        'status' => 'new'
                    ]);

                    // Count platform distribution
                    $detectedPlatform = $this->detectPlatform($lead, null, $excelLead['platform']);
                    if ($detectedPlatform === 'facebook') {
                        $fbCount++;
                    } else {
                        $igCount++;
                    }

                    // Send to CRM with Excel platform information
                    try {
                        $campaignName = $excelLead['campaign_name'] ?? null;
                        $excelPlatform = $excelLead['platform'] ?? null;
                        
                        $this->sendToTutterflyyCRM($lead, $fieldData, $campaignName, $excelPlatform);
                        $crmSuccessCount++;
                    } catch (\Exception $e) {
                        Log::error('CRM send failed for Excel import:', [
                            'lead_id' => $lead->id,
                            'excel_platform' => $excelPlatform ?? 'unknown',
                            'error' => $e->getMessage()
                        ]);
                        $crmErrorCount++;
                    }

                    $importedCount++;

                } catch (\Exception $e) {
                    Log::error('Error processing Excel lead:', [
                        'lead_data' => $excelLead,
                        'error' => $e->getMessage()
                    ]);
                    $skippedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'platforms_processed' => 'Facebook and Instagram ONLY',
                'imported_new' => $importedCount,
                'already_existed' => $skippedCount,
                'crm_success' => $crmSuccessCount,
                'crm_errors' => $crmErrorCount,
                'platform_breakdown' => [
                    'facebook_leads' => $fbCount,
                    'instagram_leads' => $igCount
                ],
                'message' => "Successfully imported {$importedCount} leads. FB: {$fbCount}, IG: {$igCount}. CRM: {$crmSuccessCount} success, {$crmErrorCount} errors."
            ]);

        } catch (\Exception $e) {
            Log::error('Error importing from Excel:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to import leads from Excel: ' . $e->getMessage()], 500);
        }
    }

    /**
     * FB/IG Excel row processor
     */
    public function processExcelRow($row)
    {
        // Only handle fb/ig from Excel
        $platform = strtolower($row['platform'] ?? 'fb');
        if (!in_array($platform, ['fb', 'facebook', 'ig', 'instagram', 'insta'])) {
            $platform = 'fb'; // Default to Facebook
        }
        
        return [
            'ad_id' => $row['ad_id'] ?? null,
            'ad_name' => $row['ad_name'] ?? null,
            'adset_id' => $row['adset_id'] ?? null,
            'adset_name' => $row['adset_name'] ?? null,
            'campaign_id' => $row['campaign_id'] ?? null,
            'campaign_name' => $row['campaign_name'] ?? null,
            'form_id' => $row['form_id'] ?? null,
            'form_name' => $row['form_name'] ?? null,
            'is_organic' => $row['is_organic'] ?? false,
            'platform' => $platform, // Only fb or ig
            'number_of_people_travelling?' => $row['number_of_people_travelling?'] ?? null,
            'when_are_you_planning_to_travel?' => $row['when_are_you_planning_to_travel?'] ?? null,
            'full_name' => $row['full_name'] ?? null,
            'phone_number' => $row['phone_number'] ?? null,
            'city' => $row['city'] ?? null,
            'lead_status' => $row['lead_status'] ?? 'new'
        ];
    }

    /**
     * Test ONLY FB/IG detection
     */
    public function testFbIgDetection(Request $request)
    {
        try {
            $testData = [
                // Facebook tests
                ['platform' => 'fb', 'expected' => 'facebook'],
                ['platform' => 'facebook', 'expected' => 'facebook'],
                
                // Instagram tests  
                ['platform' => 'ig', 'expected' => 'instagram'],
                ['platform' => 'instagram', 'expected' => 'instagram'],
                ['platform' => 'insta', 'expected' => 'instagram'],
                
                // Edge cases
                ['platform' => 'unknown', 'expected' => 'facebook'],
                ['platform' => '', 'expected' => 'facebook'],
                ['platform' => null, 'expected' => 'facebook']
            ];

            $results = [];
            
            foreach ($testData as $test) {
                $mockLead = (object) ['id' => 999, 'facebook_lead_id' => null, 'form_id' => null];
                $detectedPlatform = $this->detectPlatform($mockLead, null, $test['platform']);
                $platformInfo = $this->getPlatformSourceInfo($detectedPlatform);
                
                $results[] = [
                    'input_platform' => $test['platform'] ?? 'null',
                    'detected_platform' => $detectedPlatform,
                    'expected_platform' => $test['expected'],
                    'is_correct' => $detectedPlatform === $test['expected'],
                    'source' => $platformInfo['source'],
                    'source_medium' => $detectedPlatform . '_lead_ad',
                    'website' => $this->getPlatformWebsite($detectedPlatform),
                    'detection_method' => $this->getDetectionMethod($mockLead, null, $test['platform'])
                ];
            }
            
            $passed = count(array_filter($results, function($r) { return $r['is_correct']; }));
            
            return response()->json([
                'success' => true,
                'platform_support' => 'Facebook (fb) and Instagram (ig) ONLY',
                'supported_values' => ['fb', 'facebook', 'ig', 'instagram', 'insta'],
                'test_results' => $results,
                'summary' => [
                    'total_tests' => count($results),
                    'passed' => $passed,
                    'failed' => count($results) - $passed,
                    'success_rate' => round(($passed / count($results)) * 100, 2) . '%'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Bulk update platform for existing leads
     */
    public function bulkUpdatePlatforms(Request $request)
    {
        try {
            $limit = $request->get('limit', 100);
            $leads = Lead::limit($limit)->get();
            
            $updated = 0;
            $fbCount = 0;
            $igCount = 0;
            
            foreach ($leads as $lead) {
                $token = $this->getWorkingToken();
                $campaignName = null;
                
                // Try to get campaign name
                if ($lead->campaign_id) {
                    $campaignName = $this->getCampaignNameFromId($lead->campaign_id, $token);
                } elseif ($lead->ad_id) {
                    $adDetails = $this->getAdNameFromId($lead->ad_id, $token);
                    if ($adDetails) {
                        $campaignName = $adDetails['ad_name'];
                    }
                }
                
                $detectedPlatform = $this->detectPlatform($lead, $campaignName);
                
                if ($detectedPlatform === 'facebook') {
                    $fbCount++;
                } else {
                    $igCount++;
                }
                
                $updated++;
            }
            
            return response()->json([
                'success' => true,
                'platforms_detected' => 'Facebook and Instagram ONLY',
                'updated_count' => $updated,
                'platform_breakdown' => [
                    'facebook_leads' => $fbCount,
                    'instagram_leads' => $igCount
                ],
                'message' => "Analyzed {$updated} leads. FB: {$fbCount}, IG: {$igCount}"
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Frontend pages
    public function dashboard() { return view('crm.dashboard'); }
    public function leadsPage() { return view('crm.leads'); }

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