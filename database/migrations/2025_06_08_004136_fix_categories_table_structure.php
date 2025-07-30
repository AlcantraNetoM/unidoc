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
        Schema::table('categories', function (Blueprint $table) {
            // Adicionar a coluna empresa_id se não existir
            if (!Schema::hasColumn('categories', 'empresa_id')) {
                $table->foreignId('empresa_id')->nullable()->constrained('empresas')->onDelete('cascade')->after('id');
            }
        });
        
        // Renomear a coluna name para nome se a coluna name existir
        if (Schema::hasColumn('categories', 'name') && !Schema::hasColumn('categories', 'nome')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->renameColumn('name', 'nome');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Remover a foreign key e a coluna empresa_id
            $table->dropForeign(['empresa_id']);
            $table->dropColumn('empresa_id');
            
            // Renomear de volta nome para name
            $table->renameColumn('nome', 'name');
        });
    }
};
