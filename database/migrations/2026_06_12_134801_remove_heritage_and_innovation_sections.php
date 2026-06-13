<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\HomeSection;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove "Our Heritage" and "Innovation in Sports" sections
        HomeSection::where('type', 'image_with_text')
            ->orWhere('title', 'Innovation in Sports')
            ->orWhere('title', 'Our Heritage')
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-adding is complex as settings might be lost, but we can re-seed if needed.
    }
};
