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
        Schema::create('product_bundles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->nullable()->constrained('stores')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->enum('pricing_type', ['fixed', 'percentage_discount', 'sum_of_products'])->default('fixed');
            $table->decimal('bundle_price', 10, 2)->nullable(); // For fixed pricing
            $table->decimal('discount_percentage', 5, 2)->nullable(); // For percentage discount
            $table->decimal('minimum_price', 10, 2)->nullable(); // Minimum bundle price
            $table->decimal('maximum_price', 10, 2)->nullable(); // Maximum bundle price
            $table->enum('availability_logic', ['all_available', 'any_available', 'custom'])->default('all_available');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->json('settings')->nullable(); // Additional bundle settings
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['store_id', 'is_active']);
            $table->index('slug');
            $table->index('sort_order');
        });

        Schema::create('bundle_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bundle_id')->constrained('product_bundles')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->decimal('item_price', 10, 2)->nullable(); // Override product price
            $table->boolean('is_required')->default(true); // Can customer remove this item?
            $table->boolean('is_default_selected')->default(true); // Pre-selected in UI
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['bundle_id', 'sort_order']);
            $table->unique(['bundle_id', 'product_id', 'variant_id']);
        });

        Schema::create('bundle_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bundle_id')->constrained('product_bundles')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->timestamps();
            
            $table->unique(['bundle_id', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bundle_categories');
        Schema::dropIfExists('bundle_items');
        Schema::dropIfExists('product_bundles');
    }
};