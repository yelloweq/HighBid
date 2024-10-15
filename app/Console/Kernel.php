<?php

namespace App\Console;

use App\Jobs\ClearUnusedImagesFromStorage;
use App\Models\Auction;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(new ClearUnusedImagesFromStorage)->withoutOverlapping()->everyFiveMinutes();
        //this is not scalable, as it queries all auctions
        //TODO: Use event-based approach and schedule an individual job to end the auction when its created.
        //TODO: Remember to edit the end auction job delay when user edits auctions end time.
        $schedule->command('auctions:end')->withoutOverlapping()->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
