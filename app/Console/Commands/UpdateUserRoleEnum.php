<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateUserRoleEnum extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:user-role-enum';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the role column enum in users table to include super_admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating user role enum...');
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'general_technician', 'normal_technician') DEFAULT 'normal_technician'");
        $this->info('User role enum updated successfully.');
        return 0;
    }
} 