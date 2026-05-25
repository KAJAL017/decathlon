<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();

            // Identity
            $table->string('code')->unique()->comment('e.g. SUMMER20');
            $table->string('name');
            $table->text('description')->nullable();

            // Discount type
            $table->enum('discount_type', ['percentage','fixed_amount','free_shipping','buy_x_get_y'])
                  ->default('percentage');
            $table->decimal('discount_value', 10, 2)->default(0);
            $table->decimal('max_discount_amount', 10, 2)->nullable();

            // Buy X Get Y
            $table->integer('buy_quantity')->nullable();
            $table->integer('get_quantity')->nullable();
            $table->decimal('get_discount_percent', 5, 2)->nullable()->default(100);

            // Conditions
            $table->decimal('min_order_amount', 10, 2)->nullable();
            $table->integer('min_quantity')->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('usage_per_user')->nullable()->default(1);
            $table->integer('used_count')->default(0);

            // Applicability
            $table->enum('applies_to', ['all','specific_products','specific_categories','specific_brands'])
                  ->default('all');
            $table->json('applicable_ids')->nullable();

            // Customer restriction
            $table->enum('customer_eligibility', ['all','new_customers','specific_customers'])
                  ->default('all');

            // Scheduling
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            // Combination
            $table->boolean('combine_with_other_coupons')->default(false);
            $table->boolean('combine_with_promotions')->default(true);

            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index('code');
            $table->index('is_active');
            $table->index('expires_at');
            $table->index('discount_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
