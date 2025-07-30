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
        Schema::table('files', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['empresa_id']);
            // Make empresa_id nullable
            $table->foreignId('empresa_id')->nullable()->change();
            // Re-add foreign key constraint
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['empresa_id']);
            // Make empresa_id not nullable again
            $table->foreignId('empresa_id')->change();
            // Re-add foreign key constraint
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });
    }
};
