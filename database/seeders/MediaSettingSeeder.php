<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MediaSetting;

class MediaSettingSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'products' => ['max_width' => 1200, 'max_height' => 1200, 'format' => 'webp', 'quality' => 85],
            'categories' => ['max_width' => 800, 'max_height' => 800, 'format' => 'webp', 'quality' => 85],
            'subcategories' => ['max_width' => 800, 'max_height' => 800, 'format' => 'webp', 'quality' => 85],
            'brands' => ['max_width' => 600, 'max_height' => 600, 'format' => 'webp', 'quality' => 85],
            'banners' => ['max_width' => 1920, 'max_height' => 700, 'format' => 'webp', 'quality' => 90],
            'sliders' => ['max_width' => 1920, 'max_height' => 700, 'format' => 'webp', 'quality' => 90],
            'blogs' => ['max_width' => 1200, 'max_height' => 630, 'format' => 'webp', 'quality' => 85],
            'gallery' => ['max_width' => 1600, 'max_height' => 1600, 'format' => 'webp', 'quality' => 85],
            'testimonials' => ['max_width' => 400, 'max_height' => 400, 'format' => 'webp', 'quality' => 80],
            'users' => ['max_width' => 400, 'max_height' => 400, 'format' => 'webp', 'quality' => 80],
            'logo' => ['max_width' => 500, 'max_height' => 200, 'format' => 'png', 'quality' => 90],
            'favicon' => ['max_width' => 64, 'max_height' => 64, 'format' => 'png', 'quality' => 100],
            'cms' => ['max_width' => 1200, 'max_height' => 1200, 'format' => 'webp', 'quality' => 85],
            'seo' => ['max_width' => 1200, 'max_height' => 630, 'format' => 'webp', 'quality' => 85],
            'og' => ['max_width' => 1200, 'max_height' => 630, 'format' => 'webp', 'quality' => 85],
        ];

        foreach ($types as $type => $settings) {
            MediaSetting::updateOrCreate(
                ['image_type' => $type],
                array_merge([
                    'keep_aspect_ratio' => true,
                    'prevent_upscaling' => true,
                    'auto_optimize' => true,
                    'generate_thumbnail' => true,
                    'thumbnail_width' => 150,
                    'thumbnail_height' => 150,
                ], $settings)
            );
        }
    }
}
