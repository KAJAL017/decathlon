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
        Schema::create('media_settings', function (Blueprint $table) {
            $table->id();
            $table->string('image_type')->unique(); // product, category, banner, etc.
            $table->integer('max_width')->default(1200);
            $table->integer('max_height')->default(1200);
            $table->integer('quality')->default(85);
            $table->string('format')->default('webp'); // webp, jpg, png, original
            $table->boolean('keep_aspect_ratio')->default(true);
            $table->boolean('prevent_upscaling')->default(true);
            $table->boolean('auto_optimize')->default(true);
            $table->boolean('generate_thumbnail')->default(false);
            $table->integer('thumbnail_width')->nullable();
            $table->integer('thumbnail_height')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_settings');
    }
};
