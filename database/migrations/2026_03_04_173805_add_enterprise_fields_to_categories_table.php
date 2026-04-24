<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Add new fields
            $table->string('icon_url')->nullable()->after('banner_id');
            $table->string('icon_id')->nullable()->after('icon_url');
            $table->boolean('is_featured')->default(false)->after('is_active');
            $table->boolean('show_in_menu')->default(true)->after('is_featured');
            $table->integer('products_count')->default(0)->after('show_in_menu');
            $table->softDeletes();
            
            // Add indexes
            $table->index('is_featured');
            $table->index('show_in_menu');
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'icon_url',
                'icon_id',
                'is_featured',
                'show_in_menu',
                'products_count'
            ]);
            $table->dropSoftDeletes();
        });
    }
};
