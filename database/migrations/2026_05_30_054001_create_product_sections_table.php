<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            
            // e.g., 'features', 'specifications', 'benefits', 'applications', 'reviews', 'testimonials', 'custom_content', 'banner'
            $table->string('type'); 
            
            $table->string('title')->nullable(); // Section Header
            $table->string('subtitle')->nullable(); // Section Subheader
            
            // Flexible content based on type (JSON structure)
            $table->json('content')->nullable(); 
            
            // Presentation settings
            $table->json('settings')->nullable(); // e.g., {"bg_color": "#fff", "layout": "grid", "mobile_layout": "scroll"}
            
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            $table->index(['product_id', 'sort_order']);
            $table->index(['product_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_sections');
    }
};
