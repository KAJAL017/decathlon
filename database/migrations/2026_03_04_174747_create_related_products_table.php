<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('related_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('related_product_id')->constrained('products')->cascadeOnDelete();
            $table->enum('type', ['related', 'upsell', 'cross_sell'])->default('related');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            
            $table->unique(['product_id', 'related_product_id', 'type'], 'unique_product_relation');
            $table->index('product_id');
            $table->index('related_product_id');
            $table->index('type');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('related_products');
    }
};
