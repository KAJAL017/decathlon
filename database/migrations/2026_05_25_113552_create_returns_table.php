<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number')->unique(); // RET-2026-00001
            $table->unsignedBigInteger('order_id');
            $table->string('status')->default('requested'); // requested, approved, rejected, received, refunded, closed
            $table->string('type')->default('return'); // return, exchange, refund_only
            $table->string('reason'); // damaged, wrong_item, not_as_described, changed_mind, other
            $table->text('description')->nullable();
            $table->decimal('refund_amount', 12, 2)->default(0);
            $table->string('refund_method')->nullable(); // original_payment, bank_transfer, store_credit
            $table->string('refund_reference')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->text('admin_note')->nullable();
            $table->unsignedBigInteger('handled_by')->nullable();
            $table->json('items')->nullable(); // which items are being returned
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->index('status');
            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
