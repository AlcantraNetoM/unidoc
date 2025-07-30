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
        Schema::table('empresas', function (Blueprint $table) {
            $table->string('payment_proof_path')->nullable()->after('secondary_color');
            $table->timestamp('registration_date')->nullable()->after('payment_proof_path');
            $table->timestamp('trial_start_date')->nullable()->after('registration_date');
            $table->timestamp('trial_end_date')->nullable()->after('trial_start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn([
                'payment_proof_path',
                'registration_date',
                'trial_start_date',
                'trial_end_date'
            ]);
        });
    }
};
