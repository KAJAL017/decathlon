<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku_prefix')->nullable();
            
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            
            $table->enum('product_type', ['simple', 'variable', 'digital', 'service'])->default('simple');
            $table->enum('status', ['draft', 'active', 'inactive'])->default('draft');
            
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('is_best_seller')->default(false);
            
            // Dimensions (for shipping)
            $table->decimal('weight', 10, 2)->nullable()->comment('in kg');
            $table->decimal('length', 10, 2)->nullable()->comment('in cm');
            $table->decimal('width', 10, 2)->nullable()->comment('in cm');
            $table->decimal('height', 10, 2)->nullable()->comment('in cm');
            
            // SEO
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_keywords')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('slug');
            $table->index('sku_prefix');
            $table->index('brand_id');
            $table->index('category_id');
            $table->index('product_type');
            $table->index('status');
            $table->index(['is_featured', 'is_new', 'is_best_seller']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
