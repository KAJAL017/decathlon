<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductTag;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tagGroups = [
            // ─────────────────────────────────────────────
            // Floor 1 — Activity / Sport Tags
            // ─────────────────────────────────────────────
            ['name' => 'Running',        'slug' => 'running',        'description' => 'Products for running and jogging',          'sort_order' => 1],
            ['name' => 'Football',       'slug' => 'football',       'description' => 'Products for football players',             'sort_order' => 2],
            ['name' => 'Cricket',        'slug' => 'cricket',        'description' => 'Products for cricket enthusiasts',          'sort_order' => 3],
            ['name' => 'Basketball',     'slug' => 'basketball',     'description' => 'Products for basketball players',           'sort_order' => 4],
            ['name' => 'Tennis',         'slug' => 'tennis',         'description' => 'Products for tennis players',               'sort_order' => 5],
            ['name' => 'Badminton',      'slug' => 'badminton',      'description' => 'Products for badminton players',            'sort_order' => 6],
            ['name' => 'Swimming',       'slug' => 'swimming',       'description' => 'Products for swimmers',                     'sort_order' => 7],
            ['name' => 'Cycling',        'slug' => 'cycling',        'description' => 'Products for cyclists',                     'sort_order' => 8],
            ['name' => 'Yoga',           'slug' => 'yoga',           'description' => 'Products for yoga practitioners',           'sort_order' => 9],
            ['name' => 'Gym & Fitness',  'slug' => 'gym-fitness',    'description' => 'Products for gym and fitness training',     'sort_order' => 10],
            ['name' => 'Hiking',         'slug' => 'hiking',         'description' => 'Products for hiking and trekking',          'sort_order' => 11],
            ['name' => 'Camping',        'slug' => 'camping',        'description' => 'Products for camping and outdoor stays',    'sort_order' => 12],
            ['name' => 'Skiing',         'slug' => 'skiing',         'description' => 'Products for skiing on the slopes',         'sort_order' => 13],
            ['name' => 'Surfing',        'slug' => 'surfing',        'description' => 'Products for surfing and water sports',     'sort_order' => 14],

            // ─────────────────────────────────────────────
            // Floor 2 — Product Feature Tags
            // ─────────────────────────────────────────────
            ['name' => 'Waterproof',     'slug' => 'waterproof',     'description' => 'Water-resistant and waterproof products',   'sort_order' => 20],
            ['name' => 'Lightweight',    'slug' => 'lightweight',    'description' => 'Lightweight and portable products',         'sort_order' => 21],
            ['name' => 'Breathable',     'slug' => 'breathable',     'description' => 'Products with breathable fabric/material',  'sort_order' => 22],
            ['name' => 'UV Protection',  'slug' => 'uv-protection',  'description' => 'Products offering UV protection',           'sort_order' => 23],
            ['name' => 'Anti-Odour',     'slug' => 'anti-odour',     'description' => 'Products with anti-odour treatment',        'sort_order' => 24],
            ['name' => 'Quick-Dry',      'slug' => 'quick-dry',      'description' => 'Products that dry quickly',                 'sort_order' => 25],
            ['name' => 'Reflective',     'slug' => 'reflective',     'description' => 'Products with reflective elements for safety','sort_order'=> 26],
            ['name' => 'Eco-Friendly',   'slug' => 'eco-friendly',   'description' => 'Products made from sustainable materials',  'sort_order' => 27],
            ['name' => 'Recycled',       'slug' => 'recycled',       'description' => 'Products made from recycled materials',     'sort_order' => 28],
            ['name' => 'Anti-Slip',      'slug' => 'anti-slip',      'description' => 'Products with anti-slip properties',        'sort_order' => 29],

            // ─────────────────────────────────────────────
            // Floor 3 — Target Audience Tags
            // ─────────────────────────────────────────────
            ['name' => 'Men',            'slug' => 'men',            'description' => 'Products designed for men',                 'sort_order' => 40],
            ['name' => 'Women',          'slug' => 'women',          'description' => 'Products designed for women',               'sort_order' => 41],
            ['name' => 'Kids',           'slug' => 'kids',           'description' => 'Products designed for children',            'sort_order' => 42],
            ['name' => 'Unisex',         'slug' => 'unisex',         'description' => 'Products suitable for all genders',         'sort_order' => 43],
            ['name' => 'Beginner',       'slug' => 'beginner',       'description' => 'Products ideal for beginners',              'sort_order' => 44],
            ['name' => 'Professional',   'slug' => 'professional',   'description' => 'Products for professional athletes',        'sort_order' => 45],
            ['name' => 'Senior',         'slug' => 'senior',         'description' => 'Products suited for senior users',          'sort_order' => 46],

            // ─────────────────────────────────────────────
            // Floor 4 — Promotional / Seasonal Tags
            // ─────────────────────────────────────────────
            ['name' => 'New Arrival',    'slug' => 'new-arrival',    'description' => 'Newly launched products',                   'sort_order' => 50],
            ['name' => 'Best Seller',    'slug' => 'best-seller',    'description' => 'Top-selling products',                      'sort_order' => 51],
            ['name' => 'On Sale',        'slug' => 'on-sale',        'description' => 'Products currently on sale',                'sort_order' => 52],
            ['name' => 'Clearance',      'slug' => 'clearance',      'description' => 'Clearance sale items',                      'sort_order' => 53],
            ['name' => 'Limited Edition','slug' => 'limited-edition','description' => 'Limited edition products',                  'sort_order' => 54],
            ['name' => 'Summer',         'slug' => 'summer',         'description' => 'Products for the summer season',            'sort_order' => 55],
            ['name' => 'Winter',         'slug' => 'winter',         'description' => 'Products for the winter season',            'sort_order' => 56],
            ['name' => 'Monsoon',        'slug' => 'monsoon',        'description' => 'Products ideal for monsoon/rainy season',   'sort_order' => 57],
            ['name' => 'Gift Idea',      'slug' => 'gift-idea',      'description' => 'Products perfect as gifts',                 'sort_order' => 58],
            ['name' => 'Bundle Deal',    'slug' => 'bundle-deal',    'description' => 'Products available as bundle deals',        'sort_order' => 59],
        ];

        foreach ($tagGroups as $tag) {
            ProductTag::firstOrCreate(
                ['slug' => $tag['slug']],
                array_merge($tag, ['status' => true, 'products_count' => 0])
            );
        }

        $this->command->info('✅ Tags seeded — ' . ProductTag::count() . ' total tags across 4 floors.');
    }
}
