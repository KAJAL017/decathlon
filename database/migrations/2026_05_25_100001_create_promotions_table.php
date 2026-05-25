<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();

            // Basic info
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Type: percentage | fixed_amount | free_shipping | buy_x_get_y | flash_sale | bundle
            $table->enum('type', ['percentage','fixed_amount','free_shipping','buy_x_get_y','flash_sale','bundle'])
                  ->default('percentage');

            // Discount value
            $table->decimal('discount_value', 10, 2)->default(0)
                  ->comment('% or flat amount');
            $table->decimal('max_discount_amount', 10, 2)->nullable()
                  ->comment('Cap for percentage discounts');

            // Buy X Get Y
            $table->integer('buy_quantity')->nullable();
            $table->integer('get_quantity')->nullable();
            $table->decimal('get_discount_percent', 5, 2)->nullable()->default(100);

            // Conditions
            $table->decimal('min_order_amount', 10, 2)->nullable();
            $table->integer('min_quantity')->nullable();
            $table->integer('usage_limit')->nullable()->comment('Total uses allowed');
            $table->integer('usage_per_user')->nullable()->default(1);
            $table->integer('used_count')->default(0);

            // Applicability
            $table->enum('applies_to', ['all','specific_products','specific_categories','specific_brands'])
                  ->default('all');
            $table->json('applicable_ids')->nullable()
                  ->comment('Product/category/brand IDs');

            // Scheduling
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            // Display
            $table->string('badge_text', 50)->nullable()->comment('e.g. "50% OFF", "FLASH SALE"');
            $table->string('badge_color', 20)->nullable()->default('#ef4444');
            $table->string('banner_url')->nullable();
            $table->boolean('show_countdown')->default(false);
            $table->boolean('show_on_homepage')->default(false);

            // Priority (higher = applied first)
            $table->integer('priority')->default(0);

            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index('type');
            $table->index('is_active');
            $table->index('starts_at');
            $table->index('ends_at');
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
