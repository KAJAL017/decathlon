<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Stock fields on products (for simple/digital/service products)
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('manage_stock')->default(false)->after('availability_status')
                  ->comment('Whether to track stock quantity');
            $table->integer('stock_quantity')->default(0)->after('manage_stock')
                  ->comment('Total stock for simple products');
            $table->integer('low_stock_threshold')->default(5)->after('stock_quantity')
                  ->comment('Alert when stock falls below this');
            $table->boolean('allow_backorder')->default(false)->after('low_stock_threshold');
            $table->index('stock_quantity');
            $table->index('manage_stock');
        });

        // Stock fields on variants (for variable products)
        Schema::table('product_variants', function (Blueprint $table) {
            $table->integer('stock_quantity')->default(0)->after('cost_price')
                  ->comment('Stock quantity for this variant');
            $table->integer('low_stock_threshold')->default(5)->after('stock_quantity');
            $table->boolean('manage_stock')->default(true)->after('low_stock_threshold');
            $table->boolean('allow_backorder')->default(false)->after('manage_stock');
            $table->index('stock_quantity');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['stock_quantity']);
            $table->dropIndex(['manage_stock']);
            $table->dropColumn(['manage_stock', 'stock_quantity', 'low_stock_threshold', 'allow_backorder']);
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropIndex(['stock_quantity']);
            $table->dropColumn(['stock_quantity', 'low_stock_threshold', 'manage_stock', 'allow_backorder']);
        });
    }
};
