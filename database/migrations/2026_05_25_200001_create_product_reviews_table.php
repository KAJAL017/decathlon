<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('reviewer_name');
            $table->string('reviewer_email')->nullable();
            $table->tinyInteger('rating')->unsigned()->default(5)->comment('1-5 stars');
            $table->string('title', 255)->nullable();
            $table->text('body');
            $table->enum('status', ['pending','approved','rejected','spam'])->default('pending');
            $table->boolean('verified_purchase')->default(false);
            $table->boolean('featured')->default(false);
            $table->json('images')->nullable()->comment('Array of image URLs');
            $table->integer('helpful_count')->default(0);
            $table->string('source', 50)->nullable()->default('admin')->comment('admin, website, import');
            $table->text('admin_reply')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->foreignId('replied_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('product_id');
            $table->index('status');
            $table->index('rating');
            $table->index('featured');
        });
    }
    public function down(): void { Schema::dropIfExists('product_reviews'); }
};
