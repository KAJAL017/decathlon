<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            
            // Collection Type
            $table->enum('type', ['manual', 'auto'])->default('manual')->comment('Manual or Auto-generated');
            
            // Auto-collection rules (JSON)
            $table->json('rules')->nullable()->comment('Rules for auto collections');
            
            // Display settings
            $table->enum('visibility', ['visible', 'hidden'])->default('visible');
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            
            // SEO
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            
            // Stats cache
            $table->unsignedInteger('products_count')->default(0);
            
            $table->boolean('status')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('slug');
            $table->index('type');
            $table->index('status');
            $table->index('is_featured');
            $table->index('sort_order');
        });

        Schema::create('collection_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('collections')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            
            $table->unique(['collection_id', 'product_id']);
            $table->index('collection_id');
            $table->index('product_id');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_products');
        Schema::dropIfExists('collections');
    }
};
