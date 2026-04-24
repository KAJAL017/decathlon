<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            
            $table->string('question');
            $table->text('answer');
            
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('status')->default(true);
            
            $table->unsignedInteger('helpful_count')->default(0)->comment('How many found this helpful');
            $table->unsignedInteger('not_helpful_count')->default(0)->comment('How many found this not helpful');
            
            $table->timestamps();
            
            $table->index('product_id');
            $table->index('status');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_faqs');
    }
};
