<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()
                  ->comment('User who made the change');
            
            $table->string('version_number')->comment('Version number like 1.0, 1.1, etc.');
            
            $table->enum('change_type', [
                'created',
                'price_changed',
                'description_updated',
                'seo_updated',
                'status_changed',
                'images_updated',
                'variants_updated',
                'other'
            ])->comment('Type of change made');
            
            $table->text('change_summary')->nullable()->comment('Brief summary of changes');
            
            // Store snapshot of important fields
            $table->json('data_snapshot')->comment('JSON snapshot of product data at this version');
            
            // Track specific changes
            $table->json('changes')->nullable()->comment('Detailed field-level changes');
            
            $table->timestamp('created_at');
            
            $table->index('product_id');
            $table->index('user_id');
            $table->index('change_type');
            $table->index('version_number');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_versions');
    }
};
