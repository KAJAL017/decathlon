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
        Schema::create('product_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->nullable()->constrained('stores')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->cascadeOnDelete();
            $table->date('date')->index();
            $table->integer('views')->default(0);
            $table->integer('unique_views')->default(0);
            $table->integer('add_to_cart')->default(0);
            $table->integer('remove_from_cart')->default(0);
            $table->integer('orders')->default(0);
            $table->integer('quantity_sold')->default(0);
            $table->decimal('revenue', 12, 2)->default(0);
            $table->decimal('profit', 12, 2)->default(0);
            $table->integer('returns')->default(0);
            $table->decimal('return_amount', 12, 2)->default(0);
            $table->integer('search_appearances')->default(0);
            $table->integer('search_clicks')->default(0);
            $table->decimal('bounce_rate', 5, 2)->default(0); // Percentage
            $table->integer('time_on_page')->default(0); // Seconds
            $table->timestamps();
            
            $table->unique(['product_id', 'variant_id', 'date']);
            $table->index(['store_id', 'date']);
            $table->index(['product_id', 'date']);
            $table->index('date');
        });

        Schema::create('product_performance_summary', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->nullable()->constrained('stores')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->enum('period', ['daily', 'weekly', 'monthly', 'yearly'])->index();
            $table->date('period_start');
            $table->date('period_end');
            $table->integer('total_views')->default(0);
            $table->integer('total_unique_views')->default(0);
            $table->integer('total_add_to_cart')->default(0);
            $table->integer('total_orders')->default(0);
            $table->integer('total_quantity_sold')->default(0);
            $table->decimal('total_revenue', 12, 2)->default(0);
            $table->decimal('total_profit', 12, 2)->default(0);
            $table->decimal('conversion_rate', 5, 2)->default(0); // Percentage
            $table->decimal('cart_conversion_rate', 5, 2)->default(0); // Percentage
            $table->decimal('average_order_value', 10, 2)->default(0);
            $table->decimal('profit_margin', 5, 2)->default(0); // Percentage
            $table->integer('ranking_views')->nullable(); // Ranking among all products
            $table->integer('ranking_revenue')->nullable();
            $table->integer('ranking_orders')->nullable();
            $table->timestamps();
            
            $table->unique(['product_id', 'period', 'period_start']);
            $table->index(['store_id', 'period', 'period_start']);
            $table->index(['period', 'period_start']);
        });

        Schema::create('product_search_terms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->nullable()->constrained('stores')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('search_term')->index();
            $table->date('date')->index();
            $table->integer('appearances')->default(0);
            $table->integer('clicks')->default(0);
            $table->integer('position')->default(0); // Average position in search results
            $table->timestamps();
            
            $table->unique(['product_id', 'search_term', 'date']);
            $table->index(['store_id', 'date']);
            $table->index(['search_term', 'date']);
        });

        Schema::create('product_competitor_analysis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->nullable()->constrained('stores')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('competitor_name');
            $table->string('competitor_product_name');
            $table->string('competitor_url')->nullable();
            $table->decimal('competitor_price', 10, 2);
            $table->decimal('our_price', 10, 2);
            $table->decimal('price_difference', 10, 2); // Positive = we're more expensive
            $table->decimal('price_difference_percentage', 5, 2);
            $table->enum('price_position', ['lower', 'same', 'higher']);
            $table->date('tracked_date')->index();
            $table->json('additional_data')->nullable(); // Reviews, ratings, etc.
            $table->timestamps();
            
            $table->index(['product_id', 'tracked_date']);
            $table->index(['store_id', 'tracked_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_competitor_analysis');
        Schema::dropIfExists('product_search_terms');
        Schema::dropIfExists('product_performance_summary');
        Schema::dropIfExists('product_metrics');
    }
};