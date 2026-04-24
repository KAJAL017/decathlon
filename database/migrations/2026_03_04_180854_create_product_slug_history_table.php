<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_slug_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('old_slug');
            $table->string('new_slug');
            $table->timestamp('changed_at');
            
            $table->index('product_id');
            $table->index('old_slug');
            $table->index('new_slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_slug_history');
    }
};
