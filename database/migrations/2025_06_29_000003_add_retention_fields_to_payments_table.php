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
        Schema::table('payments', function (Blueprint $table) {
            $table->boolean('is_renewal')->default(false)->after('status');
            $table->integer('subscription_month')->nullable()->after('is_renewal')->comment('Mês da assinatura (1=primeiro, 2=segundo, etc)');
            $table->date('subscription_start_date')->nullable()->after('subscription_month');
            $table->date('subscription_end_date')->nullable()->after('subscription_start_date');
            $table->boolean('is_churned')->default(false)->after('subscription_end_date');
            $table->date('churn_date')->nullable()->after('is_churned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'is_renewal', 
                'subscription_month', 
                'subscription_start_date', 
                'subscription_end_date', 
                'is_churned', 
                'churn_date'
            ]);
        });
    }
};
