<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            
            $table->string('sku')->unique();
            $table->string('barcode')->nullable();
            
            // Pricing
            $table->decimal('price', 10, 2);
            $table->decimal('compare_price', 10, 2)->nullable()->comment('Original price for discount display');
            $table->decimal('cost_price', 10, 2)->nullable()->comment('Cost for profit calculation');
            
            // Dimensions (can override product dimensions)
            $table->decimal('weight', 10, 2)->nullable()->comment('in kg');
            $table->decimal('length', 10, 2)->nullable()->comment('in cm');
            $table->decimal('width', 10, 2)->nullable()->comment('in cm');
            $table->decimal('height', 10, 2)->nullable()->comment('in cm');
            
            $table->boolean('status')->default(true);
            
            $table->timestamps();
            
            $table->index('product_id');
            $table->index('sku');
            $table->index('barcode');
            $table->index('status');
            $table->index('price');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
