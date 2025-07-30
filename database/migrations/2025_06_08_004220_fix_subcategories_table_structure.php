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
        // Renomear a coluna name para nome se a coluna name existir
        if (Schema::hasColumn('subcategories', 'name') && !Schema::hasColumn('subcategories', 'nome')) {
            Schema::table('subcategories', function (Blueprint $table) {
                $table->renameColumn('name', 'nome');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subcategories', function (Blueprint $table) {
            // Renomear de volta nome para name
            $table->renameColumn('nome', 'name');
        });
    }
};
