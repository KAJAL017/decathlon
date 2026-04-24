<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attributes', function (Blueprint $table) {
            $table->foreignId('group_id')->nullable()->after('id')->constrained('attribute_groups')->onDelete('set null');
            $table->enum('display_type', ['dropdown', 'radio', 'checkbox', 'color_swatch'])->default('dropdown')->after('type');
            $table->boolean('is_searchable')->default(false)->after('is_required');
            $table->string('unit', 50)->nullable()->after('is_searchable');
        });
        
        // Update type enum to include multiselect and boolean
        DB::statement("ALTER TABLE attributes MODIFY COLUMN type ENUM('select', 'multiselect', 'color', 'text', 'number', 'boolean') NOT NULL DEFAULT 'select'");
    }

    public function down(): void
    {
        Schema::table('attributes', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropColumn(['group_id', 'display_type', 'is_searchable', 'unit']);
        });
        
        DB::statement("ALTER TABLE attributes MODIFY COLUMN type ENUM('select', 'color', 'text', 'number') NOT NULL DEFAULT 'select'");
    }
};
