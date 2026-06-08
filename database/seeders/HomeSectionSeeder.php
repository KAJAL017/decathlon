<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomeSection;

class HomeSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HomeSection::truncate();

        $sections = [
            [
                'type' => 'hero_categories',
                'title' => 'Top Categories',
                'settings' => ['limit' => 8],
                'sort_order' => 1,
            ],
            [
                'type' => 'hero_banners',
                'settings' => ['position' => 'hero'],
                'sort_order' => 2,
            ],
            [
                'type' => 'category_grid',
                'settings' => ['limit' => 8],
                'sort_order' => 3,
            ],
            [
                'type' => 'product_slider',
                'title' => 'Most Popular Products',
                'settings' => ['filter' => 'featured', 'limit' => 10],
                'sort_order' => 4,
            ],
            [
                'type' => 'promo_banners',
                'settings' => ['position' => 'promo'],
                'sort_order' => 5,
            ],
            [
                'type' => 'banner_grid',
                'title' => 'EQUIPPING CHAMPIONS',
                'settings' => ['position' => 'equipping-champions', 'limit' => 4],
                'sort_order' => 6,
            ],
            [
                'type' => 'featured_categories',
                'title' => 'Sports Categories',
                'settings' => ['limit' => 12],
                'sort_order' => 7,
            ],
            [
                'type' => 'product_slider',
                'title' => 'MOST LOVED DEALS! TOO GOOD TO MISS.',
                'settings' => ['filter' => 'deals', 'limit' => 10],
                'sort_order' => 8,
            ],
            [
                'type' => 'banner_grid',
                'title' => 'LEVEL UP YOUR WORKOUT !',
                'settings' => ['position' => 'workout-level-up', 'limit' => 4, 'style' => 'overlay'],
                'sort_order' => 9,
            ],
            [
                'type' => 'banner_grid',
                'title' => 'FROM THE COAST TO THE CLUB !',
                'settings' => ['position' => 'coast-club', 'limit' => 4, 'style' => 'overlay'],
                'sort_order' => 10,
            ],
            [
                'type' => 'price_points',
                'title' => 'Pick what you love, at your price!',
                'settings' => ['prices' => [499, 999, 1499, 1999]],
                'sort_order' => 11,
            ],
            [
                'type' => 'product_slider_side',
                'title' => 'Engineered for Every play!',
                'subtitle' => 'Engineered for',
                'settings' => ['filter' => 'latest', 'limit' => 10],
                'sort_order' => 12,
            ],
            [
                'type' => 'product_slider_side',
                'title' => 'Gear up for Every workout!',
                'subtitle' => 'Gear up for',
                'settings' => ['filter' => 'trending', 'limit' => 10],
                'sort_order' => 13,
            ],
            [
                'type' => 'brands',
                'title' => 'SHOP BY BRAND',
                'sort_order' => 14,
            ],
            [
                'type' => 'accordion',
                'title' => 'Our Sport Collections',
                'sort_order' => 15,
            ],
        ];

        foreach ($sections as $section) {
            HomeSection::create(array_merge($section, ['is_active' => true]));
        }
    }
}
