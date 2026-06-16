<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'image_url' => 'banners/banner-01.jpg',
                'banner_link' => '/shop?category=rackets',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'image_url' => 'banners/banner-02.jpg',
                'banner_link' => '/shop?category=hiking',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'image_url' => 'banners/banner-03.jpg',
                'banner_link' => '/shop?category=running',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }
}
