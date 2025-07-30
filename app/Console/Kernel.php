<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Check for account expirations daily at 9:00 AM
        $schedule->command('accounts:check-expiration')->dailyAt('09:00');
        
        // Also check every 6 hours to catch expirations throughout the day
        $schedule->command('accounts:check-expiration')->everySixHours();
        
        // Clean expired password reset codes every hour
        $schedule->command('auth:clean-reset-codes')->hourly();
        
        // Clean temporary PDF files every hour
        $schedule->command('tools:cleanup-temp')->hourly();
        
        // ... existing code ...
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
        // ... existing code ...
    }

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\UpdateUserRoleEnum::class,
        \App\Console\Commands\CheckAccountExpirations::class,
        // ... existing code ...
    ];
} 