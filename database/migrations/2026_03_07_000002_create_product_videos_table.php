<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            
            $table->enum('provider', ['youtube', 'vimeo', 'direct'])->default('youtube')
                  ->comment('Video hosting provider');
            
            $table->string('video_url')->comment('Full video URL');
            $table->string('video_id')->nullable()->comment('Video ID from provider');
            $table->string('thumbnail_url')->nullable()->comment('Video thumbnail');
            
            $table->unsignedInteger('duration')->nullable()->comment('Video duration in seconds');
            $table->unsignedInteger('sort_order')->default(0);
            
            $table->boolean('is_featured')->default(false)->comment('Show as main video');
            $table->boolean('status')->default(true);
            
            $table->timestamps();
            
            $table->index('product_id');
            $table->index('provider');
            $table->index('is_featured');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_videos');
    }
};
