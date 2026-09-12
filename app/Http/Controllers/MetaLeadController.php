<?php

namespace App\Http\Controllers;

use App\Lead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaLeadController extends Controller
{
    private $appId;
    private $appSecret;
    private $pageId;
    private $verifyToken;
    private $pageAccessToken;
    private $userAccessToken;
    private $crmToken;
    private $formIds;

    public function __construct()
    {
        $this->appId = env('META_APP_ID');
        $this->appSecret = env('META_APP_SECRET');
        $this->pageId = env('META_PAGE_ID');
        $this->verifyToken = env('META_VERIFY_TOKEN');
        $this->pageAccessToken = env('META_LONG_LIVED_PAGE_TOKEN');
        $this->userAccessToken = env('META_USER_ACCESS_TOKEN');
        $this->crmToken = env('TUTTERFLY_CRM_TOKEN');
        $this->formIds = explode(',', env('META_FORM_IDS', ''));
    }

    /**
     * Refresh and validate access tokens
     */
    private function refreshTokensIfNeeded()
    {
        try {
            // Check if page token is valid
            $tokenCheck = Http::get("https://graph.facebook.com/v21.0/debug_token", [
                'input_token' => $this->pageAccessToken,
                'access_token' => "{$this->appId}|{$this->appSecret}"
            ]);

            $tokenData = $tokenCheck->json();
            
            if (isset($tokenData['data']['expires_at'])) {
                $expiresAt = $tokenData['data']['expires_at'];
                $daysUntilExpiry = ($expiresAt - time()) / 86400;

                Log::info("📅 Token expires in {$daysUntilExpiry} days");

                // If token expires in less than 7 days, refresh it
                if ($daysUntilExpiry < 7) {
                    Log::warning("⚠️ Token expiring soon! Attempting refresh...");
                    return $this->refreshLongLivedTokens();
                }
            }

            return true;
        } catch (\Exception $e) {
            Log::error("❌ Token validation error: " . $e->getMessage());
            // Try to refresh anyway
            return $this->refreshLongLivedTokens();
        }
    }

    /**
     * Refresh long-lived tokens
     */
    private function refreshLongLivedTokens()
    {
        try {
            Log::info("🔄 Refreshing tokens...");

            // Step 1: Get long-lived user token
            $userTokenResponse = Http::get("https://graph.facebook.com/v21.0/oauth/access_token", [
                'grant_type' => 'fb_exchange_token',
                'client_id' => $this->appId,
                'client_secret' => $this->appSecret,
                'fb_exchange_token' => $this->userAccessToken
            ]);

            if (!$userTokenResponse->successful()) {
                Log::error("❌ Failed to refresh user token");
                return false;
            }

            $newUserToken = $userTokenResponse->json()['access_token'] ?? null;
            if (!$newUserToken) {
                Log::error("❌ No user token in response");
                return false;
            }

            // Step 2: Get page access token using new user token
            $pageTokenResponse = Http::get("https://graph.facebook.com/v21.0/{$this->pageId}", [
                'fields' => 'access_token',
                'access_token' => $newUserToken
            ]);

            if (!$pageTokenResponse->successful()) {
                Log::error("❌ Failed to get page token");
                return false;
            }

            $newPageToken = $pageTokenResponse->json()['access_token'] ?? null;
            if (!$newPageToken) {
                Log::error("❌ No page token in response");
                return false;
            }

            // Update .env file
            $this->updateEnvFile('META_USER_ACCESS_TOKEN', $newUserToken);
            $this->updateEnvFile('META_LONG_LIVED_PAGE_TOKEN', $newPageToken);

            // Update instance variables
            $this->userAccessToken = $newUserToken;
            $this->pageAccessToken = $newPageToken;

            Log::info("✅ Tokens refreshed successfully!");
            return true;

        } catch (\Exception $e) {
            Log::error("❌ Token refresh failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update .env file
     */
    private function updateEnvFile($key, $value)
    {
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);

        // Check if key exists
        if (preg_match("/^{$key}=.*/m", $envContent)) {
            $envContent = preg_replace(
                "/^{$key}=.*/m",
                "{$key}={$value}",
                $envContent
            );
        } else {
            $envContent .= "\n{$key}={$value}";
        }

        file_put_contents($envFile, $envContent);
        
        // Clear config cache
        \Artisan::call('config:clear');
    }

    /**
     * Webhook receiver (GET for verification, POST for leads)
     */
    public function receiveLead(Request $request)
    {
        // GET: Facebook verification
        if ($request->isMethod('get')) {
            $mode = $request->query('hub_mode');
            $token = $request->query('hub_verify_token');
            $challenge = $request->query('hub_challenge');

            if ($mode === 'subscribe' && $token === $this->verifyToken) {
                Log::info('✅ Webhook verified successfully');
                return response($challenge, 200)->header('Content-Type', 'text/plain');
            }

            Log::error('❌ Webhook verification failed');
            return response('Forbidden', 403);
        }

        // POST: Receive lead data
        try {
            $data = $request->all();
            Log::info('📩 Webhook received', ['data' => $data]);

            if (!isset($data['entry'])) {
                return response()->json(['status' => 'no entry data']);
            }

            foreach ($data['entry'] as $entry) {
                if (!isset($entry['changes'])) continue;

                foreach ($entry['changes'] as $change) {
                    if ($change['field'] !== 'leadgen') continue;

                    $leadgenId = $change['value']['leadgen_id'] ?? null;
                    $formId = $change['value']['form_id'] ?? null;
                    $adId = $change['value']['ad_id'] ?? null;
                    $pageId = $change['value']['page_id'] ?? null;
                    $createdTime = $change['value']['created_time'] ?? now();

                    if ($leadgenId) {
                        $this->fetchAndStoreLead($leadgenId, $formId, $adId, $pageId, $createdTime);
                    }
                }
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('❌ Webhook processing error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Fetch lead details from Meta API
     */
    private function fetchAndStoreLead($leadgenId, $formId, $adId, $pageId, $createdTime)
{
    try {
        // Refresh tokens if needed
        $this->refreshTokensIfNeeded();

        // Check if lead already exists (PREVENT DUPLICATES)
        $existingLead = Lead::where('facebook_lead_id', $leadgenId)->first();
        if ($existingLead) {
            Log::info("⚠️ Lead {$leadgenId} already exists, skipping");
            
            // Update form name if null
            if (empty($existingLead->form_name) && $formId) {
                $formName = $this->getFormNameCached($formId);  // ✅ Use cached method
                if ($formName) {
                    $existingLead->form_name = $formName;
                    $existingLead->save();
                    Log::info("✅ Updated form name for existing lead {$leadgenId}: {$formName}");
                }
            }
            
            return;
        }
        $leadCreatedAt = Carbon::parse($createdTime);
$cutoff = $this->leadSyncCutoff();

if ($leadCreatedAt->lt($cutoff)) {
    Log::info("Skipping old lead {$leadgenId}. Lead time: {$leadCreatedAt->toDateTimeString()}, cutoff: {$cutoff->toDateTimeString()}");
    return;
}

// Fetch lead from Meta
$response = Http::timeout(10)->retry(3, 100)->get(...);
        // Fetch lead from Meta
        $response = Http::timeout(10)->retry(3, 100)->get("https://graph.facebook.com/v21.0/{$leadgenId}", [
            'access_token' => $this->pageAccessToken,
            'fields' => 'id,created_time,field_data'
        ]);

        if (!$response->successful()) {
            Log::error("❌ Failed to fetch lead {$leadgenId}: " . $response->body());
            return;
        }

        $leadData = $response->json();
        $fieldData = $leadData['field_data'] ?? [];

        // Extract common fields
        $extractedFields = $this->extractFields($fieldData);

        // Fetch form name (use cached version)
        $formName = $this->getFormNameCached($formId);  // ✅ This should return actual name
        
        // ✅ Add logging to debug
        Log::info("📝 Form ID: {$formId}, Form Name: " . ($formName ?? 'NULL'));

        // Create lead in database
        $lead = Lead::create([
            'facebook_lead_id' => $leadgenId,
            'form_id' => $formId,
            'form_name' => $formName,  // ✅ This should be actual name, not ID
            'ad_id' => $adId,
            'campaign_id' => null,
            'page_id' => $pageId,
            'field_data' => $fieldData,
            'extracted_fields' => $extractedFields,
            'name' => $extractedFields['name'] ?? null,
            'email' => $extractedFields['email'] ?? null,
            'phone' => $extractedFields['phone'] ?? null,
            'status' => 'new',
            'sync_status' => 'pending',
            'created_time' => Carbon::parse($createdTime)
        ]);

        Log::info("✅ Lead {$leadgenId} stored with form name: " . ($formName ?? 'NULL'));

        // Sync to CRM immediately
        $this->syncLeadToCRM($lead);

    } catch (\Exception $e) {
        Log::error("❌ Error fetching lead {$leadgenId}: " . $e->getMessage());
    }
}

    /**
     * Fetch form name from Meta API
     */
     private function fetchFormName($formId)
    {
        if (!$formId) return null;

        try {
            // Increase timeout and add retry logic
            $response = Http::timeout(10) // Increase from 5 to 10 seconds
                ->retry(3, 100) // Retry 3 times with 100ms delay
                ->get("https://graph.facebook.com/v21.0/{$formId}", [
                    'access_token' => $this->pageAccessToken,
                    'fields' => 'name'
                ]);

            if ($response->successful()) {
                $formData = $response->json();
                $formName = $formData['name'] ?? null;
                
                if ($formName) {
                    Log::info("✅ Fetched form name: {$formName} for ID: {$formId}");
                    return $formName;
                }
            }

            // If failed, return null instead of "Form {ID}"
            Log::warning("⚠️ Could not fetch form name for ID: {$formId}");
            return null;

        } catch (\Exception $e) {
            Log::error("❌ Error fetching form name for {$formId}: " . $e->getMessage());
            return null;
        }
    }
    /**
     * Get form name with caching
     */
    private function getFormNameCached($formId)
    {
        if (!$formId) return null;

        $cacheKey = "form_name_{$formId}";
        
        // Check cache first (store for 24 hours)
        return \Cache::remember($cacheKey, 86400, function () use ($formId) {
            return $this->fetchFormName($formId);
        });
    }

    /**
     * Extract common fields from field_data
     */
    private function extractFields($fieldData)
    {
        $extracted = [
            'name' => null,
            'email' => null,
            'phone' => null
        ];

        foreach ($fieldData as $field) {
            $name = strtolower($field['name'] ?? '');
            $value = $field['values'][0] ?? null;

            if (in_array($name, ['full_name', 'name', 'first_name'])) {
                $extracted['name'] = $value;
            } elseif ($name === 'email') {
                $extracted['email'] = $value;
            } elseif (in_array($name, ['phone', 'phone_number', 'mobile'])) {
                $extracted['phone'] = $value;
            }
        }

        return $extracted;
    }

    /**
     * Sync lead to Tutterfly CRM
     */
    private function syncLeadToCRM($lead)
    {
        try {
            // Check if already synced to prevent duplicate sends
            if ($lead->isSynced()) {
                Log::info("⚠️ Lead {$lead->facebook_lead_id} already synced, skipping CRM sync");
                return;
            }

            // Prepare CRM data
            $crmData = [
                'name' => $lead->name ?? 'Unknown',
                'email' => $lead->email,
                'phone' => $lead->phone,
                'source' => 'Facebook Lead Ads',
                'form_name' => $lead->form_name,
                'ad_id' => $lead->ad_id,
                'facebook_lead_id' => $lead->facebook_lead_id,
                'custom_fields' => $lead->field_data
            ];

            // Send to CRM
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->crmToken,
                'Content-Type' => 'application/json'
            ])->post('https://dookleads.tutterflycrm.com/api/webhook', $crmData);

            if ($response->successful()) {
                $lead->update([
                    'sync_status' => 'synced',
                    'synced_at' => now(),
                    'sync_error' => null
                ]);

                Log::info("✅ Lead {$lead->facebook_lead_id} synced to CRM successfully");
            } else {
                throw new \Exception($response->body());
            }

        } catch (\Exception $e) {
            $lead->update([
                'sync_status' => 'failed',
                'sync_error' => $e->getMessage()
            ]);

            Log::error("❌ CRM sync failed for lead {$lead->facebook_lead_id}: " . $e->getMessage());
        }
    }

    /**
     * Scheduled sync (runs every 2 minutes)
     */
 /**
 * Scheduled sync (runs every 5 minutes)
 */
public function scheduledSync()
{
    Log::info('🔄 Running scheduled sync...');

    try {
        // 1. Sync ONLY truly pending leads (not synced, not failed with too many attempts)
        $pendingLeads = Lead::where('sync_status', 'pending')
            ->where(function($query) {
                $query->whereNull('sync_attempts')
                      ->orWhere('sync_attempts', '<', 3);
            })
            ->limit(50)
            ->get();
        
        if ($pendingLeads->count() > 0) {
            Log::info("📤 Syncing {$pendingLeads->count()} pending leads to CRM");
            
            foreach ($pendingLeads as $lead) {
                $this->syncLeadToCRM($lead);
                usleep(100000); // 100ms delay between syncs
            }
        } else {
            Log::info("✅ No pending leads to sync");
        }

        // 2. Retry ONLY failed leads (not synced ones)
        $failedLeads = Lead::where('sync_status', 'failed')
            ->where(function($query) {
                $query->whereNull('sync_attempts')
                      ->orWhere('sync_attempts', '<', 3);
            })
            ->limit(10)
            ->get();
        
        if ($failedLeads->count() > 0) {
            Log::info("🔁 Retrying {$failedLeads->count()} failed leads");
            
            foreach ($failedLeads as $lead) {
                // Increment attempt counter
                $currentAttempts = $lead->sync_attempts ?? 0;
                $lead->update(['sync_attempts' => $currentAttempts + 1]);
                
                $this->syncLeadToCRM($lead);
                usleep(100000); // 100ms delay
            }
        }

        // 3. Fetch ONLY NEW leads from Meta (not old ones)
        $this->fetchRecentLeads();

        Log::info('✅ Scheduled sync completed successfully');

    } catch (\Exception $e) {
        Log::error('❌ Scheduled sync failed: ' . $e->getMessage());
    }
}


    /**
     * Update missing form names
     */
    private function updateMissingFormNames()
    {
        $leadsWithoutFormName = Lead::whereNull('form_name')
            ->orWhere('form_name', '')
            ->orWhere('form_name', 'like', 'Form %') // Also fix "Form 123" entries
            ->whereNotNull('form_id')
            ->limit(20) // Process only 20 at a time to avoid timeout
            ->get();

        if ($leadsWithoutFormName->count() > 0) {
            Log::info("📝 Updating {$leadsWithoutFormName->count()} leads with missing form names");

            foreach ($leadsWithoutFormName as $lead) {
                $formName = $this->getFormNameCached($lead->form_id);
                
                if ($formName) {
                    $lead->update(['form_name' => $formName]);
                    Log::info("✅ Updated form name for lead {$lead->facebook_lead_id}: {$formName}");
                } else {
                    Log::warning("⚠️ Skipping lead {$lead->facebook_lead_id} - couldn't fetch form name");
                }
                
                // Small delay to avoid rate limiting
                usleep(200000); // 200ms delay between requests
            }
        }
    }

  /**
 * Fetch ONLY NEW leads from Meta API (after last stored lead)
 */
private function fetchRecentLeads()
{
    try {
        foreach ($this->formIds as $formId) {
            $formId = trim($formId);
            if (empty($formId)) continue;

            // Get the LAST lead we have for this form
            $lastLead = Lead::where('form_id', $formId)
                ->whereNotNull('created_time')
                ->orderBy('created_time', 'desc')
                ->first();

            if ($lastLead) {
                Log::info("🔍 Fetching NEW leads for form {$formId} after " . $lastLead->created_time->format('Y-m-d H:i:s'));
            } else {
                Log::info("🔍 Fetching ALL leads for form {$formId} (first sync)");
            }

            // Build request parameters
            $params = [
                'access_token' => $this->pageAccessToken,
                'fields' => 'id,created_time,field_data',
                'limit' => 100,
                'sort' => 'created_time_descending' // Get newest first
            ];

            $cutoff = $this->leadSyncCutoff();
$fetchAfter = $cutoff;

if ($lastLead && $lastLead->created_time) {
    $lastLeadTime = Carbon::parse($lastLead->created_time);

    if ($lastLeadTime->gt($cutoff)) {
        $fetchAfter = $lastLeadTime;
    }
}

$params['filtering'] = json_encode([[
    'field' => 'time_created',
    'operator' => 'GREATER_THAN',
    'value' => $fetchAfter->timestamp
]]);

            $response = Http::timeout(10)->retry(3, 100)
                ->get("https://graph.facebook.com/v21.0/{$formId}/leads", $params);

            if (!$response->successful()) {
                Log::error("❌ Failed to fetch leads for form {$formId}: " . $response->body());
                continue;
            }

            $leads = $response->json()['data'] ?? [];

            if (empty($leads)) {
                Log::info("✅ No new leads for form {$formId}");
                continue;
            }

            $newCount = 0;
            foreach ($leads as $leadData) {
                $leadgenId = $leadData['id'];
                $createdTime = $leadData['created_time'] ?? now();

                // Double-check it doesn't already exist
                if (Lead::where('facebook_lead_id', $leadgenId)->exists()) {
                    Log::info("⚠️ Lead {$leadgenId} already exists, skipping");
                    continue;
                }

                // Store the new lead
                $this->fetchAndStoreLead($leadgenId, $formId, null, $this->pageId, $createdTime);
                $newCount++;
            }

            Log::info("✅ Processed {$newCount} NEW leads for form {$formId}");
        }

    } catch (\Exception $e) {
        Log::error("❌ Error fetching recent leads: " . $e->getMessage());
    }
}

    /**
     * Manual sync endpoint
     */
    public function manualSync()
    {
        Log::info('🔧 Manual sync triggered');
        $this->scheduledSync();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Manual sync completed',
            'pending_count' => Lead::pending()->count(),
            'synced_count' => Lead::synced()->count(),
            'failed_count' => Lead::failed()->count()
        ]);
    }

    /**
     * Full sync (all forms)
     */
    public function fullSync()
    {
        Log::info('🔧 Full sync triggered');
        
        try {
            $this->refreshTokensIfNeeded();
            $this->updateMissingFormNames();
            $this->fetchRecentLeads();
            
            // Sync all pending
            $pendingLeads = Lead::pending()->get();
            foreach ($pendingLeads as $lead) {
                $this->syncLeadToCRM($lead);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Full sync completed',
                'total_leads' => Lead::count(),
                'pending' => Lead::pending()->count(),
                'synced' => Lead::synced()->count(),
                'failed' => Lead::failed()->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sync status
     */
    public function getSyncStatus()
    {
        return response()->json([
            'total_leads' => Lead::count(),
            'pending' => Lead::pending()->count(),
            'synced' => Lead::synced()->count(),
            'failed' => Lead::failed()->count(),
            'recent_24h' => Lead::recent(24)->count(),
            'last_lead' => Lead::latest()->first(),
            'tokens_valid' => $this->checkTokensValid()
        ]);
    }

    /**
     * Check if tokens are valid
     */
    private function checkTokensValid()
    {
        try {
            $response = Http::get("https://graph.facebook.com/v21.0/debug_token", [
                'input_token' => $this->pageAccessToken,
                'access_token' => "{$this->appId}|{$this->appSecret}"
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Test webhook setup
     */
    public function testWebhookSetup()
    {
        return response()->json([
            'app_id' => $this->appId,
            'page_id' => $this->pageId,
            'webhook_url' => url('/api/meta/webhook'),
            'verify_token' => $this->verifyToken,
            'tokens_valid' => $this->checkTokensValid()
        ]);
    }

    /**
     * Test connection
     */
    public function testConnection()
    {
        try {
            $response = Http::get("https://graph.facebook.com/v21.0/{$this->pageId}", [
                'access_token' => $this->pageAccessToken,
                'fields' => 'name,id'
            ]);

            return response()->json([
                'status' => $response->successful() ? 'success' : 'failed',
                'page_data' => $response->json()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get page token
     */
    public function getPageToken()
    {
        try {
            $this->refreshLongLivedTokens();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Tokens refreshed and saved to .env'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Debug form names
     */
    public function debugFormNames()
    {
        $forms = [];
        
        foreach ($this->formIds as $formId) {
            $formId = trim($formId);
            if (empty($formId)) continue;

            $formName = $this->fetchFormName($formId);
            $forms[] = [
                'form_id' => $formId,
                'form_name' => $formName
            ];
        }

        return response()->json([
            'forms' => $forms,
            'leads_without_form_name' => Lead::whereNull('form_name')->count()
        ]);
    }

    /**
     * Test sample lead
     */
    public function testSampleLead()
    {
        // This will test the entire flow
        Log::info('🧪 Testing sample lead creation');

        $sampleLead = Lead::create([
            'facebook_lead_id' => 'test_' . time(),
            'form_id' => $this->formIds[0] ?? null,
            'form_name' => 'Test Form',
            'field_data' => [
                ['name' => 'full_name', 'values' => ['Test User']],
                ['name' => 'email', 'values' => ['test@example.com']],
                ['name' => 'phone', 'values' => ['1234567890']]
            ],
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '1234567890',
            'status' => 'new',
            'sync_status' => 'pending',
            'created_time' => now()
        ]);

        // Try to sync
        $this->syncLeadToCRM($sampleLead);

        return response()->json([
            'status' => 'success',
            'lead' => $sampleLead,
            'sync_status' => $sampleLead->fresh()->sync_status
        ]);
    }
    private function leadSyncCutoff()
{
    return Carbon::createFromFormat(
        'Y-m-d H:i:s',
        '2026-06-12 00:00:00',
        config('app.timezone', 'Asia/Kolkata')
    );
}
}
