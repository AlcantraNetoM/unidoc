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
            $table->dropForeign(['category_id']);
            // Make category_id nullable
            $table->foreignId('category_id')->nullable()->change();
            // Re-add foreign key constraint
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['category_id']);
            // Make category_id not nullable again
            $table->foreignId('category_id')->change();
            // Re-add foreign key constraint
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }
};
