<?php

namespace App\Console\Commands;

use App\Models\PasswordResetCode;
use Illuminate\Console\Command;

class CleanExpiredResetCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:clean-reset-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean expired password reset codes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deletedCount = PasswordResetCode::where('expires_at', '<', now())
            ->orWhere('is_used', true)
            ->count();

        PasswordResetCode::clearExpired();

        $this->info("Removed {$deletedCount} expired/used password reset codes.");
        
        return 0;
    }
}
