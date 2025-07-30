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
        // Criar tabela de pagamentos
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_type'); // 'user' ou 'empresa'
            $table->unsignedBigInteger('entity_id'); // ID do usuário ou empresa
            $table->integer('months_paid'); // Número de meses pagos
            $table->decimal('amount', 10, 2); // Valor pago
            $table->string('payment_proof_path')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('payment_date');
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('approved_by')->references('id')->on('users');
        });

        // Adicionar campos para controle de expiração nas tabelas existentes
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'subscription_end_date')) {
                $table->timestamp('subscription_end_date')->nullable()->after('trial_end_date');
            }
            if (!Schema::hasColumn('users', 'account_status')) {
                $table->enum('account_status', ['trial', 'active', 'expired', 'suspended'])->default('trial')->after('subscription_end_date');
            }
            if (!Schema::hasColumn('users', 'total_months_paid')) {
                $table->integer('total_months_paid')->default(0)->after('account_status');
            }
            if (!Schema::hasColumn('users', 'last_notification_sent')) {
                $table->timestamp('last_notification_sent')->nullable()->after('total_months_paid');
            }
        });

        Schema::table('empresas', function (Blueprint $table) {
            if (!Schema::hasColumn('empresas', 'subscription_end_date')) {
                $table->timestamp('subscription_end_date')->nullable()->after('trial_end_date');
            }
            if (!Schema::hasColumn('empresas', 'account_status')) {
                $table->enum('account_status', ['trial', 'active', 'expired', 'suspended'])->default('trial')->after('subscription_end_date');
            }
            if (!Schema::hasColumn('empresas', 'total_months_paid')) {
                $table->integer('total_months_paid')->default(0)->after('account_status');
            }
            if (!Schema::hasColumn('empresas', 'last_notification_sent')) {
                $table->timestamp('last_notification_sent')->nullable()->after('total_months_paid');
            }
        });

        // Criar tabela de notificações
        Schema::create('account_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('notifiable_type'); // 'user' ou 'empresa'
            $table->unsignedBigInteger('notifiable_id');
            $table->string('type'); // 'expiry_warning', 'account_suspended', 'payment_approved'
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('sent_at');
            $table->timestamps();

            $table->index(['notifiable_type', 'notifiable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_notifications');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_end_date',
                'account_status',
                'total_months_paid',
                'last_notification_sent'
            ]);
        });

        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_end_date',
                'account_status',
                'total_months_paid',
                'last_notification_sent'
            ]);
        });

        Schema::dropIfExists('payments');
    }
};
