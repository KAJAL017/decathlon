<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            
            $table->string('title'); // e.g., 'User Manual', 'Datasheet'
            $table->string('file_path');
            $table->string('file_type')->nullable(); // e.g., 'pdf', 'zip'
            $table->string('file_size')->nullable(); // e.g., '2 MB'
            $table->string('icon')->nullable(); // optional icon class or path
            
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(true);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_downloads');
    }
};
