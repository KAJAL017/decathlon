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
            // JSON column to store all responsive image sizes
            $table->json('image_responsive')->nullable()->after('image_id');
            
            // Store original dimensions
            $table->integer('image_width')->nullable()->after('image_responsive');
            $table->integer('image_height')->nullable()->after('image_width');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['image_responsive', 'image_width', 'image_height']);
        });
    }
};
