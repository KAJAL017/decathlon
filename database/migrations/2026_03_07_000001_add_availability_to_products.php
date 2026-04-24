<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('availability_status', ['in_stock', 'out_of_stock', 'pre_order', 'backorder'])
                  ->default('in_stock')
                  ->after('status')
                  ->comment('Product availability status');
            
            $table->date('available_date')->nullable()->after('availability_status')
                  ->comment('Date when product will be available (for pre-order)');
            
            $table->index('availability_status');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->enum('availability_status', ['in_stock', 'out_of_stock', 'pre_order', 'backorder'])
                  ->default('in_stock')
                  ->after('status')
                  ->comment('Variant availability status');
            
            $table->date('available_date')->nullable()->after('availability_status')
                  ->comment('Date when variant will be available (for pre-order)');
            
            $table->index('availability_status');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['availability_status']);
            $table->dropColumn(['availability_status', 'available_date']);
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropIndex(['availability_status']);
            $table->dropColumn(['availability_status', 'available_date']);
        });
    }
};
