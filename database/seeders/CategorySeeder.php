<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Floor-wise categories like a Decathlon store layout
        // Each "floor" is a top-level parent, with subcategories under it

        $floors = [
            // ─────────────────────────────────────────────
            // FLOOR 1 — Team Sports
            // ─────────────────────────────────────────────
            [
                'name'        => 'Team Sports',
                'slug'        => 'team-sports',
                'description' => 'Equipment and apparel for all team sports',
                'is_active'   => true,
                'is_featured' => true,
                'show_in_menu'=> true,
                'sort_order'  => 1,
                'children'    => [
                    ['name' => 'Football',   'slug' => 'football',   'description' => 'Footballs, boots, kits and accessories',        'sort_order' => 1],
                    ['name' => 'Basketball', 'slug' => 'basketball', 'description' => 'Basketballs, hoops and basketball gear',          'sort_order' => 2],
                    ['name' => 'Cricket',    'slug' => 'cricket',    'description' => 'Bats, balls, pads and cricket equipment',         'sort_order' => 3],
                    ['name' => 'Volleyball', 'slug' => 'volleyball', 'description' => 'Volleyballs, nets and court equipment',           'sort_order' => 4],
                    ['name' => 'Hockey',     'slug' => 'hockey',     'description' => 'Hockey sticks, balls and protective gear',        'sort_order' => 5],
                    ['name' => 'Rugby',      'slug' => 'rugby',      'description' => 'Rugballs, boots and protective equipment',        'sort_order' => 6],
                ],
            ],

            // ─────────────────────────────────────────────
            // FLOOR 2 — Racket Sports
            // ─────────────────────────────────────────────
            [
                'name'        => 'Racket Sports',
                'slug'        => 'racket-sports',
                'description' => 'Rackets, balls and accessories for racket sports',
                'is_active'   => true,
                'is_featured' => true,
                'show_in_menu'=> true,
                'sort_order'  => 2,
                'children'    => [
                    ['name' => 'Tennis',       'slug' => 'tennis',       'description' => 'Tennis rackets, balls and court accessories',  'sort_order' => 1],
                    ['name' => 'Badminton',    'slug' => 'badminton',    'description' => 'Badminton rackets, shuttles and nets',          'sort_order' => 2],
                    ['name' => 'Table Tennis', 'slug' => 'table-tennis', 'description' => 'Table tennis tables, bats and balls',           'sort_order' => 3],
                    ['name' => 'Squash',       'slug' => 'squash',       'description' => 'Squash rackets, balls and court gear',          'sort_order' => 4],
                    ['name' => 'Pickleball',   'slug' => 'pickleball',   'description' => 'Pickleball paddles, balls and accessories',     'sort_order' => 5],
                ],
            ],

            // ─────────────────────────────────────────────
            // FLOOR 3 — Fitness & Gym
            // ─────────────────────────────────────────────
            [
                'name'        => 'Fitness & Gym',
                'slug'        => 'fitness-gym',
                'description' => 'Everything for your home gym and fitness routine',
                'is_active'   => true,
                'is_featured' => true,
                'show_in_menu'=> true,
                'sort_order'  => 3,
                'children'    => [
                    ['name' => 'Cardio Equipment', 'slug' => 'cardio-equipment', 'description' => 'Treadmills, cycles and cardio machines',        'sort_order' => 1],
                    ['name' => 'Strength Training', 'slug' => 'strength-training', 'description' => 'Dumbbells, barbells, weight plates and benches', 'sort_order' => 2],
                    ['name' => 'Yoga',              'slug' => 'yoga',              'description' => 'Yoga mats, blocks, straps and accessories',      'sort_order' => 3],
                    ['name' => 'Pilates',           'slug' => 'pilates',           'description' => 'Pilates reformers, bands and accessories',       'sort_order' => 4],
                    ['name' => 'Crossfit',          'slug' => 'crossfit',          'description' => 'Kettlebells, battle ropes and crossfit gear',    'sort_order' => 5],
                    ['name' => 'Fitness Accessories','slug'=> 'fitness-accessories','description'=> 'Gloves, belts, resistance bands and more',       'sort_order' => 6],
                ],
            ],

            // ─────────────────────────────────────────────
            // FLOOR 4 — Outdoor & Nature
            // ─────────────────────────────────────────────
            [
                'name'        => 'Outdoor & Nature',
                'slug'        => 'outdoor-nature',
                'description' => 'Gear for camping, hiking and all outdoor adventures',
                'is_active'   => true,
                'is_featured' => true,
                'show_in_menu'=> true,
                'sort_order'  => 4,
                'children'    => [
                    ['name' => 'Camping',      'slug' => 'camping',      'description' => 'Tents, sleeping bags and camping essentials',    'sort_order' => 1],
                    ['name' => 'Hiking',       'slug' => 'hiking',       'description' => 'Hiking boots, backpacks and trekking poles',     'sort_order' => 2],
                    ['name' => 'Mountain Climbing', 'slug' => 'mountain-climbing', 'description' => 'Harnesses, ropes and climbing gear',   'sort_order' => 3],
                    ['name' => 'Trail Running','slug' => 'trail-running','description' => 'Trail shoes, hydration vests and accessories',   'sort_order' => 4],
                    ['name' => 'Fishing',      'slug' => 'fishing',      'description' => 'Rods, reels, lures and fishing accessories',     'sort_order' => 5],
                    ['name' => 'Horse Riding', 'slug' => 'horse-riding', 'description' => 'Helmets, saddles and equestrian equipment',      'sort_order' => 6],
                ],
            ],

            // ─────────────────────────────────────────────
            // FLOOR 5 — Water Sports
            // ─────────────────────────────────────────────
            [
                'name'        => 'Water Sports',
                'slug'        => 'water-sports',
                'description' => 'Equipment for all aquatic activities',
                'is_active'   => true,
                'is_featured' => false,
                'show_in_menu'=> true,
                'sort_order'  => 5,
                'children'    => [
                    ['name' => 'Swimming',   'slug' => 'swimming',   'description' => 'Swimwear, goggles, caps and pool accessories',    'sort_order' => 1],
                    ['name' => 'Surfing',    'slug' => 'surfing',    'description' => 'Surfboards, wetsuits and surf gear',              'sort_order' => 2],
                    ['name' => 'Kayaking',   'slug' => 'kayaking',   'description' => 'Kayaks, paddles and life jackets',               'sort_order' => 3],
                    ['name' => 'Scuba Diving','slug'=> 'scuba-diving','description' => 'Masks, fins, tanks and diving equipment',        'sort_order' => 4],
                    ['name' => 'Stand Up Paddle', 'slug' => 'sup',   'description' => 'SUP boards, paddles and accessories',            'sort_order' => 5],
                ],
            ],

            // ─────────────────────────────────────────────
            // FLOOR 6 — Cycling
            // ─────────────────────────────────────────────
            [
                'name'        => 'Cycling',
                'slug'        => 'cycling',
                'description' => 'Bicycles, accessories and cycling apparel',
                'is_active'   => true,
                'is_featured' => true,
                'show_in_menu'=> true,
                'sort_order'  => 6,
                'children'    => [
                    ['name' => 'Road Bikes',     'slug' => 'road-bikes',     'description' => 'Lightweight bikes for road cycling',          'sort_order' => 1],
                    ['name' => 'Mountain Bikes', 'slug' => 'mountain-bikes', 'description' => 'Rugged bikes for off-road trails',            'sort_order' => 2],
                    ['name' => 'City Bikes',     'slug' => 'city-bikes',     'description' => 'Comfortable bikes for urban commuting',       'sort_order' => 3],
                    ['name' => 'Electric Bikes', 'slug' => 'electric-bikes', 'description' => 'E-bikes for effortless riding',               'sort_order' => 4],
                    ['name' => 'Cycling Apparel','slug' => 'cycling-apparel','description' => 'Jerseys, shorts, helmets and gloves',         'sort_order' => 5],
                    ['name' => 'Bike Accessories','slug'=> 'bike-accessories','description' => 'Lights, locks, pumps and spare parts',       'sort_order' => 6],
                ],
            ],

            // ─────────────────────────────────────────────
            // FLOOR 7 — Winter Sports
            // ─────────────────────────────────────────────
            [
                'name'        => 'Winter Sports',
                'slug'        => 'winter-sports',
                'description' => 'Gear and apparel for snow and ice sports',
                'is_active'   => true,
                'is_featured' => false,
                'show_in_menu'=> true,
                'sort_order'  => 7,
                'children'    => [
                    ['name' => 'Skiing',      'slug' => 'skiing',      'description' => 'Skis, boots, poles and ski apparel',           'sort_order' => 1],
                    ['name' => 'Snowboarding','slug' => 'snowboarding','description' => 'Snowboards, bindings and snowboard gear',       'sort_order' => 2],
                    ['name' => 'Ice Skating', 'slug' => 'ice-skating', 'description' => 'Ice skates and accessories',                   'sort_order' => 3],
                    ['name' => 'Sledging',    'slug' => 'sledging',    'description' => 'Sleds, toboggans and snow gear',               'sort_order' => 4],
                ],
            ],

            // ─────────────────────────────────────────────
            // FLOOR 8 — Sports Apparel & Footwear
            // ─────────────────────────────────────────────
            [
                'name'        => 'Apparel & Footwear',
                'slug'        => 'apparel-footwear',
                'description' => 'Sports clothing and shoes for every activity',
                'is_active'   => true,
                'is_featured' => true,
                'show_in_menu'=> true,
                'sort_order'  => 8,
                'children'    => [
                    ['name' => 'Men\'s Clothing',  'slug' => 'mens-clothing',  'description' => 'T-shirts, shorts, track pants and more for men',  'sort_order' => 1],
                    ['name' => 'Women\'s Clothing','slug' => 'womens-clothing','description' => 'Sports tops, leggings and activewear for women',   'sort_order' => 2],
                    ['name' => 'Kids Clothing',    'slug' => 'kids-clothing',  'description' => 'Sportswear and uniforms for kids',                 'sort_order' => 3],
                    ['name' => 'Running Shoes',    'slug' => 'running-shoes',  'description' => 'Road and trail running footwear',                  'sort_order' => 4],
                    ['name' => 'Training Shoes',   'slug' => 'training-shoes', 'description' => 'Gym and cross-training footwear',                  'sort_order' => 5],
                    ['name' => 'Outdoor Footwear', 'slug' => 'outdoor-footwear','description'=> 'Hiking boots, sandals and water shoes',            'sort_order' => 6],
                ],
            ],
        ];

        foreach ($floors as $floorData) {
            $children = $floorData['children'] ?? [];
            unset($floorData['children']);

            $parent = Category::firstOrCreate(
                ['slug' => $floorData['slug']],
                array_merge($floorData, ['is_active' => true, 'show_in_menu' => true])
            );

            foreach ($children as $child) {
                Category::firstOrCreate(
                    ['slug' => $child['slug']],
                    array_merge($child, [
                        'parent_id'    => $parent->id,
                        'is_active'    => true,
                        'is_featured'  => false,
                        'show_in_menu' => true,
                    ])
                );
            }
        }

        $this->command->info('✅ Categories seeded — 8 floors with ' . Category::count() . ' total categories.');
    }
}
