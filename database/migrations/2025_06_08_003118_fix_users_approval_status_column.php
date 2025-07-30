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
        Schema::table('users', function (Blueprint $table) {
            // Verificar se a coluna approval_status não existe antes de adicioná-la
            if (!Schema::hasColumn('users', 'approval_status')) {
                $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('account_type');
            }
        });
        
        // Migrar os dados existentes se a coluna is_approved existir
        if (Schema::hasColumn('users', 'is_approved')) {
            DB::statement("UPDATE users SET approval_status = CASE WHEN is_approved = 1 THEN 'approved' ELSE 'pending' END");
            
            Schema::table('users', function (Blueprint $table) {
                // Remover a coluna is_approved
                $table->dropColumn('is_approved');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Adicionar de volta is_approved
            $table->boolean('is_approved')->default(false)->after('account_type');
        });
        
        // Migrar dados de volta
        DB::statement("UPDATE users SET is_approved = CASE WHEN approval_status = 'approved' THEN 1 ELSE 0 END");
        
        Schema::table('users', function (Blueprint $table) {
            // Remover approval_status
            $table->dropColumn('approval_status');
        });
    }
};
