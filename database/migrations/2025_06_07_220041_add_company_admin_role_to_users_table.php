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
        // Skip for SQLite as it doesn't support ENUM modifications
        if (DB::getDriverName() === 'sqlite') {
            return;
        }
        
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'general_technician', 'normal_technician', 'personal_user', 'company_admin') DEFAULT 'normal_technician'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Skip for SQLite as it doesn't support ENUM modifications
        if (DB::getDriverName() === 'sqlite') {
            return;
        }
        
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'general_technician', 'normal_technician', 'personal_user') DEFAULT 'normal_technician'");
    }
};
