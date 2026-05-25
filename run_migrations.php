<?php
// Quick migration runner - run via: php run_migrations.php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "Connecting to database...\n";

try {
    DB::connection()->getPdo();
    echo "Connected!\n";
} catch (Exception $e) {
    echo "DB Error: " . $e->getMessage() . "\n";
    exit(1);
}

// Drop and recreate email_campaigns
echo "\n--- Email Campaigns Table ---\n";
try {
    Schema::dropIfExists('email_campaigns');
    echo "Dropped email_campaigns\n";

    Schema::create('email_campaigns', function ($table) {
        $table->id();
        $table->string('name');
        $table->string('subject');
        $table->string('preview_text', 255)->nullable();
        $table->enum('type', ['newsletter','promotional','abandoned_cart','welcome','re_engagement','flash_sale'])->default('newsletter');
        $table->enum('status', ['draft','scheduled','sending','sent','paused','cancelled'])->default('draft');
        $table->text('template')->nullable();
        $table->enum('audience', ['all','new_customers','inactive_customers','specific_segment'])->default('all');
        $table->json('segment_ids')->nullable();
        $table->timestamp('scheduled_at')->nullable();
        $table->timestamp('sent_at')->nullable();
        $table->integer('total_recipients')->default(0);
        $table->integer('delivered_count')->default(0);
        $table->integer('opened_count')->default(0);
        $table->integer('clicked_count')->default(0);
        $table->integer('unsubscribed_count')->default(0);
        $table->integer('bounced_count')->default(0);
        $table->string('from_name')->nullable();
        $table->string('from_email')->nullable();
        $table->string('reply_to')->nullable();
        $table->json('tags')->nullable();
        $table->boolean('is_ab_test')->default(false);
        $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamps();
        $table->softDeletes();
        $table->index('status');
        $table->index('type');
        $table->index('scheduled_at');
    });
    echo "Created email_campaigns table\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Create stock_movements
echo "\n--- Stock Movements Table ---\n";
try {
    if (!Schema::hasTable('stock_movements')) {
        Schema::create('stock_movements', function ($table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->enum('type', ['purchase','sale','return','adjustment','transfer','damage','expired'])->default('adjustment');
            $table->integer('quantity');
            $table->integer('quantity_before')->default(0);
            $table->integer('quantity_after')->default(0);
            $table->string('reference_type')->nullable();
            $table->integer('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('cost_per_unit', 10, 2)->nullable();
            $table->string('location')->nullable()->default('Main Warehouse');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index('product_id');
            $table->index('type');
            $table->index('created_at');
        });
        echo "Created stock_movements table\n";
    } else {
        echo "stock_movements already exists\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\nDone!\n";
