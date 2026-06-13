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
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'subtitle',
                'button_text',
                'button_link',
                'background_color',
                'accent_color',
                'price_text',
                'position'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->string('title')->after('id');
            $table->string('subtitle')->nullable()->after('title');
            $table->string('button_text')->nullable()->after('subtitle');
            $table->string('button_link')->nullable()->after('button_text');
            $table->string('background_color')->default('#f5e6d3')->after('image_id');
            $table->string('accent_color')->default('#2dd4bf')->after('background_color');
            $table->string('price_text')->nullable()->after('accent_color');
            $table->string('position')->default('hero')->after('is_active');
        });
    }
};
