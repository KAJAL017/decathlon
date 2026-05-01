<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Publishing Control
            $table->timestamp('published_at')->nullable()->after('status');
            $table->timestamp('unpublished_at')->nullable()->after('published_at');
            
            // Visibility Control
            $table->enum('visibility', ['visible', 'hidden', 'catalog_only', 'search_only'])
                  ->default('visible')
                  ->after('unpublished_at');
            
            // Digital Products Support
            $table->boolean('is_digital')->default(false)->after('product_type');
            $table->string('download_url')->nullable()->after('is_digital');
            $table->integer('download_limit')->nullable()->after('download_url')->comment('Max downloads per purchase, null = unlimited');
            
            // Product Rating Cache
            $table->decimal('average_rating', 3, 2)->nullable()->after('is_best_seller')->comment('Cached from reviews');
            $table->unsignedInteger('reviews_count')->default(0)->after('average_rating');
            
            // Indexes
            $table->index('published_at');
            $table->index('visibility');
            $table->index('is_digital');
            $table->index('average_rating');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['published_at']);
            $table->dropIndex(['visibility']);
            $table->dropIndex(['is_digital']);
            $table->dropIndex(['average_rating']);
            
            $table->dropColumn([
                'published_at',
                'unpublished_at',
                'visibility',
                'is_digital',
                'download_url',
                'download_limit',
                'average_rating',
                'reviews_count',
            ]);
        });
    }
};
