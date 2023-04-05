<?php

namespace App\Console;

use App\Schedule\ClearCache;
use App\Schedule\ParseRecentLeads;
use App\Schedule\ParseRecentWebhooks;
use App\Schedule\StartQueueProcessing;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(new ParseRecentWebhooks)
            ->name('parse_recent_webhooks')
            ->withoutOverlapping()
            ->everyMinute();
        $schedule->call(new ParseRecentLeads)
            ->name('parse_recent_leads')
            ->withoutOverlapping()
            ->everyMinute();
        $schedule->exec((new StartQueueProcessing)())
            ->name('start_queue_processing')
            ->everyMinute();
        $schedule->exec((new ClearCache)())
            ->name('start_clear_cache')
            ->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
