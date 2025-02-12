<?php

namespace App\Console;

use App\Jobs\CheckBirthdayJob;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        /*$schedule->job(new CheckBirthdayJob)
            ->dailyAt('00:00')
            ->timezone('Asia/Ho_Chi_Minh')
            ->withoutOverlapping();*/
         $schedule->job(new CheckBirthdayJob)->everySecond()->timezone('Asia/Ho_Chi_Minh');
        // $schedule->command('points:expire')->daily();
        $schedule->command('membership:reset')->yearly();
        $schedule->job(new CheckBirthdayJob)->monthly()->timezone('Asia/Ho_Chi_Minh')->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        $this->load(__DIR__ . '/Commands/ExpirePoints.php');
        $this->load(__DIR__ . '/Commands/ResetMembership.php');

        require base_path('routes/console.php');
    }
}
