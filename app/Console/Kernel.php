<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\MetaLeadController;

class Kernel extends ConsoleKernel
{
    protected $commands = [];

    protected function schedule(Schedule $schedule)
    {
        // Run Meta lead sync every 5 minutes
        $schedule->call(function () {
            \Log::info('🔄 Running scheduled sync (5-min)...');
            
            $controller = new MetaLeadController();
            $controller->scheduledSync();
            
            \Log::info('✅ Meta lead sync (5-min) completed successfully');
        })
        ->everyFiveMinutes()
        ->name('meta-lead-sync')
        ->withoutOverlapping(10);
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}