<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->enum('type', ['purchase','sale','return','adjustment','transfer','damage','expired'])->default('adjustment');
            $table->integer('quantity')->comment('Positive = incoming, negative = outgoing');
            $table->integer('quantity_before')->default(0);
            $table->integer('quantity_after')->default(0);
            $table->string('reference_type')->nullable()->comment('e.g. order, purchase_order');
            $table->integer('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('cost_per_unit', 10, 2)->nullable();
            $table->string('location')->nullable()->default('Main Warehouse');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('product_id');
            $table->index('variant_id');
            $table->index('type');
            $table->index('created_at');
        });
    }

    public function down(): void {
        Schema::dropIfExists('stock_movements');
    }
};
