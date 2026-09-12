<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Lead;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FixFormNames extends Command
{
    protected $signature = 'leads:fix-form-names';
    protected $description = 'Fetch and update correct form names from Facebook API';

    private $pageAccessToken;

    public function __construct()
    {
        parent::__construct();
        $this->pageAccessToken = env('META_LONG_LIVED_PAGE_TOKEN');
    }

    public function handle()
    {
        $this->info('🔧 Starting form name fix...');
        $this->newLine();

        // Get unique form IDs
        $formIds = Lead::select('form_id')
            ->distinct()
            ->whereNotNull('form_id')
            ->pluck('form_id');

        $this->info("Found {$formIds->count()} unique form IDs");
        $this->newLine();

        $formNameMap = [];

        // Fetch form names from Facebook
        foreach ($formIds as $formId) {
            $this->line("Fetching form name for ID: {$formId}");
            
            $formName = $this->fetchFormName($formId);
            
            if ($formName) {
                $formNameMap[$formId] = $formName;
                $this->info("  ✅ {$formName}");
            } else {
                $this->error("  ❌ Could not fetch name");
            }
            
            sleep(1); // Delay to avoid rate limiting
        }

        $this->newLine();
        $this->info('📊 Form Name Map:');
        foreach ($formNameMap as $id => $name) {
            $this->line("  {$id} → {$name}");
        }

        $this->newLine();
        
        // Ask for confirmation
        if (!$this->confirm('Do you want to update the database with these form names?')) {
            $this->warn('Cancelled!');
            return 0;
        }

        // Update database
        $this->info('🔄 Updating database...');
        $totalUpdated = 0;

        foreach ($formNameMap as $formId => $formName) {
            $count = Lead::where('form_id', $formId)->update(['form_name' => $formName]);
            $totalUpdated += $count;
            $this->line("  Updated {$count} leads with form: {$formName}");
        }

        $this->newLine();
        $this->info("✅ Successfully updated {$totalUpdated} leads!");

        // Show stats
        $this->newLine();
        $this->info('📊 Final Statistics:');
        $this->line("  Total leads: " . Lead::count());
        $this->line("  With form names: " . Lead::whereNotNull('form_name')->count());
        $this->line("  Without form names: " . Lead::whereNull('form_name')->count());

        return 0;
    }

    private function fetchFormName($formId)
    {
        try {
            $response = Http::timeout(10)
                ->retry(3, 100)
                ->get("https://graph.facebook.com/v21.0/{$formId}", [
                    'access_token' => $this->pageAccessToken,
                    'fields' => 'name'
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['name'] ?? null;
            }

            return null;

        } catch (\Exception $e) {
            $this->error("  Error: " . $e->getMessage());
            return null;
        }
    }
}
