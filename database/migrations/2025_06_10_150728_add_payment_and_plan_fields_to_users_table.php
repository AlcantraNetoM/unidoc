<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'payment_proof_path')) {
                $table->string('payment_proof_path')->nullable()->after('approved_by');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'trial_start_date')) {
                $table->timestamp('trial_start_date')->nullable()->after('payment_proof_path');
            }
            if (!Schema::hasColumn('users', 'trial_end_date')) {
                $table->timestamp('trial_end_date')->nullable()->after('trial_start_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'payment_proof_path',
                'phone',
                'trial_start_date',
                'trial_end_date'
            ]);
        });
    }
};
