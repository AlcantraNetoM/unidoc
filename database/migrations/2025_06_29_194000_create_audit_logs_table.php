<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // login, logout, create, update, delete, approve, reject
            $table->string('model')->nullable(); // User, Empresa, Payment, etc
            $table->unsignedBigInteger('model_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('user_email')->nullable();
            $table->string('user_role')->nullable();
            $table->json('old_values')->nullable(); // valores antes da mudança
            $table->json('new_values')->nullable(); // valores depois da mudança
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('description')->nullable(); // descrição human-readable
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->timestamps();
            
            $table->index(['action', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['model', 'model_id']);
            $table->index(['severity', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
