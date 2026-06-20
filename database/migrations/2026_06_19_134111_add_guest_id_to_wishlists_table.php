<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wishlists', function (Blueprint $table) {
            $table->string('guest_id')->nullable()->index()->after('customer_id');
            $table->unique(['guest_id', 'product_id'], 'wishlists_guest_product_unique');
        });
    }

    public function down(): void
    {
        Schema::table('wishlists', function (Blueprint $table) {
            $table->dropIndex('wishlists_guest_product_unique');
            $table->dropColumn('guest_id');
        });
    }
};
