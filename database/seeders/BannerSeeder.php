<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Pro-Grade Rackets',
                'subtitle' => 'Engineered Performance For Every Player',
                'button_text' => 'Shop Now',
                'button_link' => '/shop?category=rackets',
                'image_url' => 'https://images.unsplash.com/photo-1617083278968-e6ee43f9b0c0?w=800&auto=format&fit=crop&q=80',
                'background_color' => '#f5e6d3',
                'accent_color' => '#2dd4bf',
                'price_text' => 'Starting from ₹299 Onwards',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Tough Hiking Boots',
                'subtitle' => 'Adventure Awaits',
                'button_text' => 'View Collection',
                'button_link' => '/shop?category=hiking-boots',
                'image_url' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?w=800&auto=format&fit=crop&q=80',
                'background_color' => '#e8d9f5',
                'accent_color' => '#10b981',
                'price_text' => 'Flat 20% OFF',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Premium Running Gear',
                'subtitle' => 'Run Further',
                'button_text' => 'Explore',
                'button_link' => '/shop?category=running',
                'image_url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&auto=format&fit=crop&q=80',
                'background_color' => '#d3e6f5',
                'accent_color' => '#f59e0b',
                'price_text' => 'Starting from ₹1299',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }
}
