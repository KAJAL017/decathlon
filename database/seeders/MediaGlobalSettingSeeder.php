<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class MediaGlobalSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'max_upload_size' => '500',
            'allowed_extensions' => 'jpg,jpeg,png,webp',
            'auto_delete_old' => '1',
            'auto_rename' => '1',
            'unique_filenames' => '1',
            'strip_metadata' => '1',
        ];

        Setting::saveMany($settings, 'media');
    }
}
