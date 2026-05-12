<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_tags', function (Blueprint $table) {
            $table->boolean('status')->default(true)->after('products_count');
            $table->integer('sort_order')->default(0)->after('status');
            
            $table->index('status');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::table('product_tags', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['sort_order']);
            $table->dropColumn(['status', 'sort_order']);
        });
    }
};
