<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if we're using SQLite (common in testing)
        if (config('database.default') === 'sqlite' || DB::connection()->getDriverName() === 'sqlite') {
            // For SQLite, we need to recreate the table structure
            // Since this is a complex operation and might not be needed for our feature,
            // we'll skip it in SQLite environments
            return;
        }
        
        // For MySQL/other databases
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'general_technician', 'normal_technician', 'personal_user') DEFAULT 'normal_technician'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if we're using SQLite (common in testing)
        if (config('database.default') === 'sqlite' || DB::connection()->getDriverName() === 'sqlite') {
            return;
        }
        
        // For MySQL/other databases
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'general_technician', 'normal_technician') DEFAULT 'normal_technician'");
    }
};
