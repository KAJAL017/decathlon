<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Seed a 3-level category tree matching Decathlon's website layout.
     *
     * Level 1: Top-level nav (Men, Women, Kids, Team Sports, Fitness & Gym, etc.)
     * Level 2: Section headers shown in mega-dropdown columns (Men Topwear, Men Bottomwear, etc.)
     * Level 3: Individual product type links listed under each column
     */
    public function run(): void
    {
        $tree = [

            // ──────────────────────────────────────────────────────────────
            // 1. MEN
            // ──────────────────────────────────────────────────────────────
            [
                'name' => 'Men', 'slug' => 'men',
                'description' => 'Sports clothing, footwear and equipment for men',
                'is_featured' => true, 'show_in_menu' => true, 'sort_order' => 1,
                'children' => [
                    [
                        'name' => 'Men Topwear', 'slug' => 'men-topwear', 'sort_order' => 1,
                        'grandchildren' => [
                            ['name' => 'Athleisure',           'slug' => 'men-athleisure'],
                            ['name' => 'Cotton T-shirt',       'slug' => 'men-cotton-tshirt'],
                            ['name' => 'Polo T-shirt',         'slug' => 'men-polo-tshirt'],
                            ['name' => 'Tank Tops',            'slug' => 'men-tank-tops'],
                            ['name' => 'Shirts',               'slug' => 'men-shirts'],
                            ['name' => 'Swim & Beach Tops',    'slug' => 'men-swim-beach-tops'],
                            ['name' => 'Sweatshirts & Hoodies','slug' => 'men-sweatshirts-hoodies'],
                            ['name' => 'Fleeces & Pullovers',  'slug' => 'men-fleeces-pullovers'],
                            ['name' => 'T-shirts Under 999',   'slug' => 'men-tshirts-under-999'],
                        ],
                    ],
                    [
                        'name' => 'Men Bottomwear', 'slug' => 'men-bottomwear', 'sort_order' => 2,
                        'grandchildren' => [
                            ['name' => 'Shorts',                   'slug' => 'men-shorts'],
                            ['name' => 'Track Pants & Joggers',    'slug' => 'men-track-pants-joggers'],
                            ['name' => 'Trousers & Chinos',        'slug' => 'men-trousers-chinos'],
                            ['name' => 'Waterproof Rain Pants',    'slug' => 'men-waterproof-rain-pants'],
                            ['name' => 'Tights & Compression',     'slug' => 'men-tights-compression'],
                            ['name' => 'Swim Costumes',            'slug' => 'men-swim-costumes'],
                            ['name' => 'Shorts Under 999',         'slug' => 'men-shorts-under-999'],
                            ['name' => 'Track Pants Under 999',    'slug' => 'men-track-pants-under-999'],
                        ],
                    ],
                    [
                        'name' => 'Men Footwear', 'slug' => 'men-footwear', 'sort_order' => 3,
                        'grandchildren' => [
                            ['name' => 'Sports Shoes',         'slug' => 'men-sports-shoes'],
                            ['name' => 'Sandals',              'slug' => 'men-sandals'],
                            ['name' => 'Flip Flops & Aqua Shoes','slug'=> 'men-flip-flops-aqua'],
                            ['name' => 'Running Shoes',        'slug' => 'men-running-shoes'],
                            ['name' => 'Walking Shoes',        'slug' => 'men-walking-shoes'],
                            ['name' => 'Outdoor Shoes & Boots','slug' => 'men-outdoor-shoes-boots'],
                            ['name' => 'Non Marking Shoes',    'slug' => 'men-non-marking-shoes'],
                            ['name' => 'Football Shoes',       'slug' => 'men-football-shoes'],
                            ['name' => 'Socks',                'slug' => 'men-socks'],
                        ],
                    ],
                    [
                        'name' => 'Jackets & Sweatshirts', 'slug' => 'men-jackets-sweatshirts', 'sort_order' => 4,
                        'grandchildren' => [
                            ['name' => 'Raincoat & Ponchos',          'slug' => 'men-raincoat-ponchos'],
                            ['name' => 'Sports Jackets',              'slug' => 'men-sports-jackets'],
                            ['name' => 'Winter Jackets',              'slug' => 'men-winter-jackets'],
                            ['name' => 'Warm & Waterproof Jackets',   'slug' => 'men-warm-waterproof-jackets'],
                            ['name' => 'Padded & Down Jackets',       'slug' => 'men-padded-down-jackets'],
                            ['name' => 'Windcheaters',                'slug' => 'men-windcheaters'],
                            ['name' => 'Jackets Under 999',           'slug' => 'men-jackets-under-999'],
                        ],
                    ],
                    [
                        'name' => 'Men Innerwear', 'slug' => 'men-innerwear', 'sort_order' => 5,
                        'grandchildren' => [
                            ['name' => 'Thermals',        'slug' => 'men-thermals'],
                            ['name' => 'Brief Underwear', 'slug' => 'men-brief-underwear'],
                            ['name' => 'Compression Wear','slug' => 'men-compression-wear'],
                        ],
                    ],
                    [
                        'name' => 'Men Sports Equipment', 'slug' => 'men-sports-equipment', 'sort_order' => 6,
                        'grandchildren' => [
                            ['name' => 'Cricket Gear',     'slug' => 'men-cricket-gear'],
                            ['name' => 'Football Gear',    'slug' => 'men-football-gear'],
                            ['name' => 'Badminton Gear',   'slug' => 'men-badminton-gear'],
                            ['name' => 'Gym Accessories',  'slug' => 'men-gym-accessories'],
                        ],
                    ],
                ],
            ],

            // ──────────────────────────────────────────────────────────────
            // 2. WOMEN
            // ──────────────────────────────────────────────────────────────
            [
                'name' => 'Women', 'slug' => 'women',
                'description' => 'Sports clothing, footwear and equipment for women',
                'is_featured' => true, 'show_in_menu' => true, 'sort_order' => 2,
                'children' => [
                    [
                        'name' => 'Women Topwear', 'slug' => 'women-topwear', 'sort_order' => 1,
                        'grandchildren' => [
                            ['name' => 'T-shirts',              'slug' => 'women-tshirts'],
                            ['name' => 'Polo T-shirts',         'slug' => 'women-polo-tshirts'],
                            ['name' => 'Tank Tops',             'slug' => 'women-tank-tops'],
                            ['name' => 'Crop Tops',             'slug' => 'women-crop-tops'],
                            ['name' => 'Sweatshirt & Hoodies',  'slug' => 'women-sweatshirts-hoodies'],
                            ['name' => 'Fleece & Pullovers',    'slug' => 'women-fleece-pullovers'],
                            ['name' => 'Swim Costumes',         'slug' => 'women-swim-costumes'],
                            ['name' => 'Activewear',            'slug' => 'women-activewear'],
                            ['name' => 'Raincoats',             'slug' => 'women-raincoats'],
                        ],
                    ],
                    [
                        'name' => 'Women Bottomwear', 'slug' => 'women-bottomwear', 'sort_order' => 2,
                        'grandchildren' => [
                            ['name' => 'Shorts',       'slug' => 'women-shorts'],
                            ['name' => 'Leggings',     'slug' => 'women-leggings'],
                            ['name' => 'Track Pants',  'slug' => 'women-track-pants'],
                            ['name' => 'Trousers',     'slug' => 'women-trousers'],
                            ['name' => 'Skirts',       'slug' => 'women-skirts'],
                            ['name' => 'Under 999',    'slug' => 'women-bottomwear-under-999'],
                        ],
                    ],
                    [
                        'name' => 'Women Footwear', 'slug' => 'women-footwear', 'sort_order' => 3,
                        'grandchildren' => [
                            ['name' => 'Sports Shoes',          'slug' => 'women-sports-shoes'],
                            ['name' => 'Sandals',               'slug' => 'women-sandals'],
                            ['name' => 'Flip Flops',            'slug' => 'women-flip-flops'],
                            ['name' => 'Running Shoes',         'slug' => 'women-running-shoes'],
                            ['name' => 'Walking Shoes',         'slug' => 'women-walking-shoes'],
                            ['name' => 'Outdoor Shoes & Boots', 'slug' => 'women-outdoor-shoes-boots'],
                            ['name' => 'Non Marking Shoes',     'slug' => 'women-non-marking-shoes'],
                            ['name' => 'Socks',                 'slug' => 'women-socks'],
                        ],
                    ],
                    [
                        'name' => 'Women Jackets', 'slug' => 'women-jackets', 'sort_order' => 4,
                        'grandchildren' => [
                            ['name' => 'Sports Jackets',        'slug' => 'women-sports-jackets'],
                            ['name' => 'Raincoats',             'slug' => 'women-jacket-raincoats'],
                            ['name' => 'Windcheaters',          'slug' => 'women-windcheaters'],
                            ['name' => 'Sweaters',              'slug' => 'women-sweaters'],
                            ['name' => 'Winter Jackets',        'slug' => 'women-winter-jackets'],
                            ['name' => 'Snow Jackets',          'slug' => 'women-snow-jackets'],
                            ['name' => 'Padded & Down Jackets', 'slug' => 'women-padded-down-jackets'],
                        ],
                    ],
                    [
                        'name' => 'Women Innerwear', 'slug' => 'women-innerwear', 'sort_order' => 5,
                        'grandchildren' => [
                            ['name' => 'Sports Bra',              'slug' => 'women-sports-bra'],
                            ['name' => 'Women Thermal Innerwear', 'slug' => 'women-thermal-innerwear'],
                            ['name' => 'Compression Tights',      'slug' => 'women-compression-tights'],
                        ],
                    ],
                    [
                        'name' => 'Women Sports Equipment', 'slug' => 'women-sports-equipment', 'sort_order' => 6,
                        'grandchildren' => [
                            ['name' => 'Yoga & Pilates',     'slug' => 'women-yoga-pilates'],
                            ['name' => 'Swimming Gear',      'slug' => 'women-swimming-gear'],
                            ['name' => 'Fitness Accessories','slug' => 'women-fitness-accessories'],
                        ],
                    ],
                ],
            ],

            // ──────────────────────────────────────────────────────────────
            // 3. KIDS
            // ──────────────────────────────────────────────────────────────
            [
                'name' => 'Kids', 'slug' => 'kids',
                'description' => 'Sports clothing, footwear and equipment for kids',
                'is_featured' => true, 'show_in_menu' => true, 'sort_order' => 3,
                'children' => [
                    [
                        'name' => 'Kids Topwear', 'slug' => 'kids-topwear', 'sort_order' => 1,
                        'grandchildren' => [
                            ['name' => 'T-shirts',           'slug' => 'kids-tshirts'],
                            ['name' => 'Sweatshirts',        'slug' => 'kids-sweatshirts'],
                            ['name' => 'Hoodies',            'slug' => 'kids-hoodies'],
                            ['name' => 'Polo T-shirts',      'slug' => 'kids-polo-tshirts'],
                            ['name' => 'Jersey & Kits',      'slug' => 'kids-jersey-kits'],
                        ],
                    ],
                    [
                        'name' => 'Kids Bottomwear', 'slug' => 'kids-bottomwear', 'sort_order' => 2,
                        'grandchildren' => [
                            ['name' => 'Shorts',        'slug' => 'kids-shorts'],
                            ['name' => 'Track Pants',   'slug' => 'kids-track-pants'],
                            ['name' => 'Leggings',      'slug' => 'kids-leggings'],
                            ['name' => 'Swim Trunks',   'slug' => 'kids-swim-trunks'],
                        ],
                    ],
                    [
                        'name' => 'Kids Footwear', 'slug' => 'kids-footwear', 'sort_order' => 3,
                        'grandchildren' => [
                            ['name' => 'Sports Shoes',       'slug' => 'kids-sports-shoes'],
                            ['name' => 'Running Shoes',      'slug' => 'kids-running-shoes'],
                            ['name' => 'Sandals & Floaters', 'slug' => 'kids-sandals-floaters'],
                            ['name' => 'Football Shoes',     'slug' => 'kids-football-shoes'],
                            ['name' => 'Socks',              'slug' => 'kids-socks'],
                        ],
                    ],
                    [
                        'name' => 'Kids Jackets', 'slug' => 'kids-jackets', 'sort_order' => 4,
                        'grandchildren' => [
                            ['name' => 'Raincoats',          'slug' => 'kids-raincoats'],
                            ['name' => 'Windcheaters',       'slug' => 'kids-windcheaters'],
                            ['name' => 'Padded Jackets',     'slug' => 'kids-padded-jackets'],
                            ['name' => 'Fleece Jackets',     'slug' => 'kids-fleece-jackets'],
                        ],
                    ],
                    [
                        'name' => 'Kids Sports Equipment', 'slug' => 'kids-sports-equipment', 'sort_order' => 5,
                        'grandchildren' => [
                            ['name' => 'Cricket Gear',    'slug' => 'kids-cricket-gear'],
                            ['name' => 'Football Gear',   'slug' => 'kids-football-gear'],
                            ['name' => 'Badminton Gear',  'slug' => 'kids-badminton-gear'],
                            ['name' => 'Swimming Gear',   'slug' => 'kids-swimming-gear'],
                            ['name' => 'Cycling Gear',    'slug' => 'kids-cycling-gear'],
                        ],
                    ],
                ],
            ],

            // ──────────────────────────────────────────────────────────────
            // 4. TEAM SPORTS
            // ──────────────────────────────────────────────────────────────
            [
                'name' => 'Team Sports', 'slug' => 'team-sports',
                'description' => 'Equipment and apparel for all team sports',
                'is_featured' => true, 'show_in_menu' => true, 'sort_order' => 4,
                'children' => [
                    [
                        'name' => 'Football', 'slug' => 'football', 'sort_order' => 1,
                        'grandchildren' => [
                            ['name' => 'Footballs',          'slug' => 'footballs'],
                            ['name' => 'Football Shoes',     'slug' => 'football-shoes'],
                            ['name' => 'Shin Guards',        'slug' => 'shin-guards'],
                            ['name' => 'Goalkeeper Gloves',  'slug' => 'goalkeeper-gloves'],
                            ['name' => 'Football Kits',      'slug' => 'football-kits'],
                            ['name' => 'Goal Posts',         'slug' => 'goal-posts'],
                        ],
                    ],
                    [
                        'name' => 'Cricket', 'slug' => 'cricket', 'sort_order' => 2,
                        'grandchildren' => [
                            ['name' => 'Cricket Bats',       'slug' => 'cricket-bats'],
                            ['name' => 'Cricket Balls',      'slug' => 'cricket-balls'],
                            ['name' => 'Batting Pads',       'slug' => 'batting-pads'],
                            ['name' => 'Batting Gloves',     'slug' => 'batting-gloves'],
                            ['name' => 'Cricket Helmets',    'slug' => 'cricket-helmets'],
                            ['name' => 'Cricket Kits',       'slug' => 'cricket-kits'],
                        ],
                    ],
                    [
                        'name' => 'Basketball', 'slug' => 'basketball', 'sort_order' => 3,
                        'grandchildren' => [
                            ['name' => 'Basketballs',        'slug' => 'basketballs'],
                            ['name' => 'Basketball Shoes',   'slug' => 'basketball-shoes'],
                            ['name' => 'Basketball Hoops',   'slug' => 'basketball-hoops'],
                            ['name' => 'Basketball Kits',    'slug' => 'basketball-kits'],
                        ],
                    ],
                    [
                        'name' => 'Volleyball', 'slug' => 'volleyball', 'sort_order' => 4,
                        'grandchildren' => [
                            ['name' => 'Volleyballs',        'slug' => 'volleyballs'],
                            ['name' => 'Volleyball Nets',    'slug' => 'volleyball-nets'],
                            ['name' => 'Knee Pads',          'slug' => 'volleyball-knee-pads'],
                        ],
                    ],
                    [
                        'name' => 'Hockey', 'slug' => 'hockey', 'sort_order' => 5,
                        'grandchildren' => [
                            ['name' => 'Hockey Sticks',      'slug' => 'hockey-sticks'],
                            ['name' => 'Hockey Balls',       'slug' => 'hockey-balls'],
                            ['name' => 'Hockey Shin Guards', 'slug' => 'hockey-shin-guards'],
                            ['name' => 'Hockey Gloves',      'slug' => 'hockey-gloves'],
                        ],
                    ],
                    [
                        'name' => 'Rugby', 'slug' => 'rugby', 'sort_order' => 6,
                        'grandchildren' => [
                            ['name' => 'Rugby Balls',        'slug' => 'rugby-balls'],
                            ['name' => 'Rugby Boots',        'slug' => 'rugby-boots'],
                            ['name' => 'Protective Gear',    'slug' => 'rugby-protective-gear'],
                        ],
                    ],
                ],
            ],

            // ──────────────────────────────────────────────────────────────
            // 5. RACKET SPORTS
            // ──────────────────────────────────────────────────────────────
            [
                'name' => 'Racket Sports', 'slug' => 'racket-sports',
                'description' => 'Rackets, balls and accessories for racket sports',
                'is_featured' => true, 'show_in_menu' => true, 'sort_order' => 5,
                'children' => [
                    [
                        'name' => 'Badminton', 'slug' => 'badminton', 'sort_order' => 1,
                        'grandchildren' => [
                            ['name' => 'Badminton Rackets',    'slug' => 'badminton-rackets'],
                            ['name' => 'Shuttlecocks',         'slug' => 'shuttlecocks'],
                            ['name' => 'Badminton Nets',       'slug' => 'badminton-nets'],
                            ['name' => 'Badminton Shoes',      'slug' => 'badminton-shoes'],
                            ['name' => 'Grip Tapes',           'slug' => 'grip-tapes'],
                            ['name' => 'Racket Bags',          'slug' => 'racket-bags'],
                        ],
                    ],
                    [
                        'name' => 'Tennis', 'slug' => 'tennis', 'sort_order' => 2,
                        'grandchildren' => [
                            ['name' => 'Tennis Rackets',       'slug' => 'tennis-rackets'],
                            ['name' => 'Tennis Balls',         'slug' => 'tennis-balls'],
                            ['name' => 'Tennis Shoes',         'slug' => 'tennis-shoes'],
                            ['name' => 'Tennis Bags',          'slug' => 'tennis-bags'],
                            ['name' => 'Tennis Strings',       'slug' => 'tennis-strings'],
                        ],
                    ],
                    [
                        'name' => 'Table Tennis', 'slug' => 'table-tennis', 'sort_order' => 3,
                        'grandchildren' => [
                            ['name' => 'TT Tables',             'slug' => 'tt-tables'],
                            ['name' => 'TT Bats',               'slug' => 'tt-bats'],
                            ['name' => 'TT Balls',              'slug' => 'tt-balls'],
                            ['name' => 'TT Nets & Posts',       'slug' => 'tt-nets-posts'],
                        ],
                    ],
                    [
                        'name' => 'Squash', 'slug' => 'squash', 'sort_order' => 4,
                        'grandchildren' => [
                            ['name' => 'Squash Rackets',       'slug' => 'squash-rackets'],
                            ['name' => 'Squash Balls',         'slug' => 'squash-balls'],
                            ['name' => 'Squash Shoes',         'slug' => 'squash-shoes'],
                        ],
                    ],
                    [
                        'name' => 'Pickleball', 'slug' => 'pickleball', 'sort_order' => 5,
                        'grandchildren' => [
                            ['name' => 'Pickleball Paddles',   'slug' => 'pickleball-paddles'],
                            ['name' => 'Pickleball Balls',     'slug' => 'pickleball-balls'],
                        ],
                    ],
                ],
            ],

            // ──────────────────────────────────────────────────────────────
            // 6. FITNESS & GYM
            // ──────────────────────────────────────────────────────────────
            [
                'name' => 'Fitness & Gym', 'slug' => 'fitness-gym',
                'description' => 'Everything for your home gym and fitness routine',
                'is_featured' => true, 'show_in_menu' => true, 'sort_order' => 6,
                'children' => [
                    [
                        'name' => 'Cardio Equipment', 'slug' => 'cardio-equipment', 'sort_order' => 1,
                        'grandchildren' => [
                            ['name' => 'Treadmills',           'slug' => 'treadmills'],
                            ['name' => 'Exercise Bikes',       'slug' => 'exercise-bikes'],
                            ['name' => 'Ellipticals',          'slug' => 'ellipticals'],
                            ['name' => 'Rowing Machines',      'slug' => 'rowing-machines'],
                            ['name' => 'Jump Ropes',           'slug' => 'jump-ropes'],
                            ['name' => 'Steppers',             'slug' => 'steppers'],
                        ],
                    ],
                    [
                        'name' => 'Strength Training', 'slug' => 'strength-training', 'sort_order' => 2,
                        'grandchildren' => [
                            ['name' => 'Dumbbells',            'slug' => 'dumbbells'],
                            ['name' => 'Barbells & Plates',    'slug' => 'barbells-plates'],
                            ['name' => 'Kettlebells',          'slug' => 'kettlebells'],
                            ['name' => 'Weight Benches',       'slug' => 'weight-benches'],
                            ['name' => 'Pull-up Bars',         'slug' => 'pull-up-bars'],
                            ['name' => 'Resistance Bands',     'slug' => 'resistance-bands'],
                        ],
                    ],
                    [
                        'name' => 'Yoga & Pilates', 'slug' => 'yoga', 'sort_order' => 3,
                        'grandchildren' => [
                            ['name' => 'Yoga Mats',            'slug' => 'yoga-mats'],
                            ['name' => 'Yoga Blocks',          'slug' => 'yoga-blocks'],
                            ['name' => 'Yoga Straps',          'slug' => 'yoga-straps'],
                            ['name' => 'Pilates Balls',        'slug' => 'pilates-balls'],
                            ['name' => 'Foam Rollers',         'slug' => 'foam-rollers'],
                        ],
                    ],
                    [
                        'name' => 'Fitness Accessories', 'slug' => 'fitness-accessories', 'sort_order' => 4,
                        'grandchildren' => [
                            ['name' => 'Gym Gloves',           'slug' => 'gym-gloves'],
                            ['name' => 'Gym Bags',             'slug' => 'gym-bags'],
                            ['name' => 'Water Bottles',        'slug' => 'water-bottles'],
                            ['name' => 'Gym Belts',            'slug' => 'gym-belts'],
                            ['name' => 'Shaker Bottles',       'slug' => 'shaker-bottles'],
                            ['name' => 'Gym Towels',           'slug' => 'gym-towels'],
                        ],
                    ],
                    [
                        'name' => 'Crossfit & Functional', 'slug' => 'crossfit', 'sort_order' => 5,
                        'grandchildren' => [
                            ['name' => 'Battle Ropes',         'slug' => 'battle-ropes'],
                            ['name' => 'Plyo Boxes',           'slug' => 'plyo-boxes'],
                            ['name' => 'Ab Rollers',           'slug' => 'ab-rollers'],
                            ['name' => 'Gymnastic Rings',      'slug' => 'gymnastic-rings'],
                        ],
                    ],
                ],
            ],

            // ──────────────────────────────────────────────────────────────
            // 7. OUTDOOR & NATURE
            // ──────────────────────────────────────────────────────────────
            [
                'name' => 'Outdoor & Nature', 'slug' => 'outdoor-nature',
                'description' => 'Gear for camping, hiking and all outdoor adventures',
                'is_featured' => true, 'show_in_menu' => true, 'sort_order' => 7,
                'children' => [
                    [
                        'name' => 'Camping', 'slug' => 'camping', 'sort_order' => 1,
                        'grandchildren' => [
                            ['name' => 'Tents',                'slug' => 'tents'],
                            ['name' => 'Sleeping Bags',        'slug' => 'sleeping-bags'],
                            ['name' => 'Sleeping Mats',        'slug' => 'sleeping-mats'],
                            ['name' => 'Camping Lanterns',     'slug' => 'camping-lanterns'],
                            ['name' => 'Camp Stoves',          'slug' => 'camp-stoves'],
                            ['name' => 'Camping Cookware',     'slug' => 'camping-cookware'],
                            ['name' => 'Hammocks',             'slug' => 'hammocks'],
                        ],
                    ],
                    [
                        'name' => 'Hiking & Trekking', 'slug' => 'hiking', 'sort_order' => 2,
                        'grandchildren' => [
                            ['name' => 'Trekking Shoes',       'slug' => 'trekking-shoes'],
                            ['name' => 'Hiking Backpacks',     'slug' => 'hiking-backpacks'],
                            ['name' => 'Trekking Poles',       'slug' => 'trekking-poles'],
                            ['name' => 'Rain Jackets',         'slug' => 'rain-jackets'],
                            ['name' => 'Headlamps',            'slug' => 'headlamps'],
                            ['name' => 'Hydration Packs',      'slug' => 'hydration-packs'],
                        ],
                    ],
                    [
                        'name' => 'Mountain Climbing', 'slug' => 'mountain-climbing', 'sort_order' => 3,
                        'grandchildren' => [
                            ['name' => 'Climbing Harnesses',   'slug' => 'climbing-harnesses'],
                            ['name' => 'Climbing Ropes',       'slug' => 'climbing-ropes'],
                            ['name' => 'Carabiners',           'slug' => 'carabiners'],
                            ['name' => 'Climbing Shoes',       'slug' => 'climbing-shoes'],
                            ['name' => 'Chalk & Chalk Bags',   'slug' => 'chalk-chalk-bags'],
                        ],
                    ],
                    [
                        'name' => 'Fishing', 'slug' => 'fishing', 'sort_order' => 4,
                        'grandchildren' => [
                            ['name' => 'Fishing Rods',         'slug' => 'fishing-rods'],
                            ['name' => 'Fishing Reels',        'slug' => 'fishing-reels'],
                            ['name' => 'Fishing Lures & Bait', 'slug' => 'fishing-lures-bait'],
                            ['name' => 'Fishing Lines',        'slug' => 'fishing-lines'],
                            ['name' => 'Tackle Boxes',         'slug' => 'tackle-boxes'],
                        ],
                    ],
                    [
                        'name' => 'Trail Running', 'slug' => 'trail-running', 'sort_order' => 5,
                        'grandchildren' => [
                            ['name' => 'Trail Shoes',          'slug' => 'trail-shoes'],
                            ['name' => 'Hydration Vests',      'slug' => 'hydration-vests'],
                            ['name' => 'Running Poles',        'slug' => 'running-poles'],
                            ['name' => 'Trail Shorts',         'slug' => 'trail-shorts'],
                        ],
                    ],
                ],
            ],

            // ──────────────────────────────────────────────────────────────
            // 8. CYCLING
            // ──────────────────────────────────────────────────────────────
            [
                'name' => 'Cycling', 'slug' => 'cycling',
                'description' => 'Bicycles, accessories and cycling apparel',
                'is_featured' => true, 'show_in_menu' => true, 'sort_order' => 8,
                'children' => [
                    [
                        'name' => 'Bikes', 'slug' => 'bikes', 'sort_order' => 1,
                        'grandchildren' => [
                            ['name' => 'Road Bikes',           'slug' => 'road-bikes'],
                            ['name' => 'Mountain Bikes',       'slug' => 'mountain-bikes'],
                            ['name' => 'City Bikes',           'slug' => 'city-bikes'],
                            ['name' => 'Electric Bikes',       'slug' => 'electric-bikes'],
                            ['name' => 'Hybrid Bikes',         'slug' => 'hybrid-bikes'],
                            ['name' => 'Kids Bikes',           'slug' => 'kids-bikes'],
                        ],
                    ],
                    [
                        'name' => 'Cycling Apparel', 'slug' => 'cycling-apparel', 'sort_order' => 2,
                        'grandchildren' => [
                            ['name' => 'Cycling Jerseys',      'slug' => 'cycling-jerseys'],
                            ['name' => 'Cycling Shorts',       'slug' => 'cycling-shorts'],
                            ['name' => 'Cycling Jackets',      'slug' => 'cycling-jackets'],
                            ['name' => 'Cycling Shoes',        'slug' => 'cycling-shoes'],
                            ['name' => 'Cycling Gloves',       'slug' => 'cycling-gloves'],
                            ['name' => 'Cycling Helmets',      'slug' => 'cycling-helmets'],
                        ],
                    ],
                    [
                        'name' => 'Bike Accessories', 'slug' => 'bike-accessories', 'sort_order' => 3,
                        'grandchildren' => [
                            ['name' => 'Bike Lights',          'slug' => 'bike-lights'],
                            ['name' => 'Bike Locks',           'slug' => 'bike-locks'],
                            ['name' => 'Bike Pumps',           'slug' => 'bike-pumps'],
                            ['name' => 'Bike Computers',       'slug' => 'bike-computers'],
                            ['name' => 'Bike Bags',            'slug' => 'bike-bags'],
                            ['name' => 'Mudguards',            'slug' => 'mudguards'],
                        ],
                    ],
                ],
            ],

            // ──────────────────────────────────────────────────────────────
            // 9. WATER SPORTS
            // ──────────────────────────────────────────────────────────────
            [
                'name' => 'Water Sports', 'slug' => 'water-sports',
                'description' => 'Equipment for all aquatic activities',
                'is_featured' => false, 'show_in_menu' => true, 'sort_order' => 9,
                'children' => [
                    [
                        'name' => 'Swimming', 'slug' => 'swimming', 'sort_order' => 1,
                        'grandchildren' => [
                            ['name' => 'Swimwear Men',         'slug' => 'swimwear-men'],
                            ['name' => 'Swimwear Women',       'slug' => 'swimwear-women'],
                            ['name' => 'Swim Goggles',         'slug' => 'swim-goggles'],
                            ['name' => 'Swim Caps',            'slug' => 'swim-caps'],
                            ['name' => 'Pool Floats & Aids',   'slug' => 'pool-floats-aids'],
                            ['name' => 'Fins & Paddles',       'slug' => 'fins-paddles'],
                        ],
                    ],
                    [
                        'name' => 'Surfing', 'slug' => 'surfing', 'sort_order' => 2,
                        'grandchildren' => [
                            ['name' => 'Surfboards',           'slug' => 'surfboards'],
                            ['name' => 'Wetsuits',             'slug' => 'wetsuits'],
                            ['name' => 'Surf Leashes',         'slug' => 'surf-leashes'],
                            ['name' => 'Surf Wax',             'slug' => 'surf-wax'],
                        ],
                    ],
                    [
                        'name' => 'Kayaking & SUP', 'slug' => 'kayaking', 'sort_order' => 3,
                        'grandchildren' => [
                            ['name' => 'Kayaks',               'slug' => 'kayaks'],
                            ['name' => 'Kayak Paddles',        'slug' => 'kayak-paddles'],
                            ['name' => 'SUP Boards',           'slug' => 'sup'],
                            ['name' => 'Life Jackets',         'slug' => 'life-jackets'],
                        ],
                    ],
                    [
                        'name' => 'Scuba & Snorkeling', 'slug' => 'scuba-diving', 'sort_order' => 4,
                        'grandchildren' => [
                            ['name' => 'Dive Masks',           'slug' => 'dive-masks'],
                            ['name' => 'Snorkels',             'slug' => 'snorkels'],
                            ['name' => 'Dive Fins',            'slug' => 'dive-fins'],
                            ['name' => 'Dive Gloves',          'slug' => 'dive-gloves'],
                        ],
                    ],
                ],
            ],

            // ──────────────────────────────────────────────────────────────
            // 10. WINTER SPORTS
            // ──────────────────────────────────────────────────────────────
            [
                'name' => 'Winter Sports', 'slug' => 'winter-sports',
                'description' => 'Gear and apparel for snow and ice sports',
                'is_featured' => false, 'show_in_menu' => true, 'sort_order' => 10,
                'children' => [
                    [
                        'name' => 'Skiing', 'slug' => 'skiing', 'sort_order' => 1,
                        'grandchildren' => [
                            ['name' => 'Skis',                 'slug' => 'skis'],
                            ['name' => 'Ski Boots',            'slug' => 'ski-boots'],
                            ['name' => 'Ski Poles',            'slug' => 'ski-poles'],
                            ['name' => 'Ski Jackets',          'slug' => 'ski-jackets'],
                            ['name' => 'Ski Pants',            'slug' => 'ski-pants'],
                            ['name' => 'Ski Helmets',          'slug' => 'ski-helmets'],
                        ],
                    ],
                    [
                        'name' => 'Snowboarding', 'slug' => 'snowboarding', 'sort_order' => 2,
                        'grandchildren' => [
                            ['name' => 'Snowboards',           'slug' => 'snowboards'],
                            ['name' => 'Snowboard Boots',      'slug' => 'snowboard-boots'],
                            ['name' => 'Snowboard Bindings',   'slug' => 'snowboard-bindings'],
                            ['name' => 'Snow Jackets',         'slug' => 'snow-jackets'],
                        ],
                    ],
                    [
                        'name' => 'Snow Accessories', 'slug' => 'snow-accessories', 'sort_order' => 3,
                        'grandchildren' => [
                            ['name' => 'Ski Goggles',          'slug' => 'ski-goggles'],
                            ['name' => 'Ski Gloves',           'slug' => 'ski-gloves'],
                            ['name' => 'Beanies & Headbands',  'slug' => 'beanies-headbands'],
                            ['name' => 'Base Layers',          'slug' => 'base-layers'],
                        ],
                    ],
                ],
            ],

        ];

        $total = 0;

        foreach ($tree as $index => $topData) {
            $children = $topData['children'] ?? [];
            unset($topData['children']);

            // Create / fetch Level 1
            $parent = Category::firstOrCreate(
                ['slug' => $topData['slug']],
                array_merge($topData, [
                    'is_active' => true,
                    'is_top' => $index < 8 // First 8 categories are top categories
                ])
            );
            $total++;

            foreach ($children as $order => $childData) {
                $grandchildren = $childData['grandchildren'] ?? [];
                unset($childData['grandchildren']);

                // Create / fetch Level 2
                $child = Category::firstOrCreate(
                    ['slug' => $childData['slug']],
                    array_merge($childData, [
                        'parent_id'    => $parent->id,
                        'is_active'    => true,
                        'is_featured'  => false,
                        'show_in_menu' => true,
                    ])
                );
                $total++;

                foreach ($grandchildren as $i => $gcData) {
                    // Create / fetch Level 3
                    Category::firstOrCreate(
                        ['slug' => $gcData['slug']],
                        array_merge($gcData, [
                            'parent_id'    => $child->id,
                            'sort_order'   => $i + 1,
                            'is_active'    => true,
                            'is_featured'  => false,
                            'show_in_menu' => true,
                        ])
                    );
                    $total++;
                }
            }
        }

        $this->command->info("✅ Categories seeded — {$total} records created/found, " . Category::count() . ' total in DB.');
    }
}
