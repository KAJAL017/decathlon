<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->json('events');                          // ['order.created','payment.success']
            $table->string('secret', 100)->nullable();       // HMAC signing secret
            $table->enum('method', ['POST','GET','PUT'])->default('POST');
            $table->json('headers')->nullable();             // custom headers
            $table->boolean('is_active')->default(true);
            $table->integer('timeout')->default(10);         // seconds
            $table->integer('retry_count')->default(3);
            $table->timestamp('last_triggered_at')->nullable();
            $table->enum('last_status', ['success','failed','pending'])->nullable();
            $table->integer('total_calls')->default(0);
            $table->integer('failed_calls')->default(0);
            $table->text('last_response')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhooks');
    }
};
