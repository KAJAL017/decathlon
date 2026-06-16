<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\VariantAttributeValue;
use App\Models\ProductTag;
use App\Models\Collection;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    private array $imageUrls = [
        'products/product-01.jpg',
        'products/product-02.jpg',
        'products/product-03.jpg',
        'products/product-04.jpg',
        'products/product-05.jpg',
        'products/product-06.jpg',
        'products/product-07.jpg',
        'products/product-08.jpg',
        'products/product-09.jpg',
        'products/product-10.jpg',
        'products/product-11.jpg',
        'products/product-12.jpg',
        'products/product-13.jpg',
        'products/product-14.jpg',
        'products/product-15.jpg',
        'products/product-16.jpg',
        'products/product-17.jpg',
        'products/product-18.jpg',
        'products/product-19.jpg',
        'products/product-20.jpg',
    ];

    private array $products = [
        // ── Running ────────────────────────────────────────────────────
        [
            'name' => 'Kalenji Ekiden One Running Shoes',
            'sku_prefix' => 'KAL-EKO',
            'brand' => 'kalenji', 'category' => 'men-running-shoes',
            'short_description' => 'Lightweight road running shoes with cushioned sole',
            'description' => '<p>Designed for regular road running, the Kalenji Ekiden One features a cushioned EVA sole for comfort on hard surfaces. The breathable mesh upper keeps feet cool during long runs. Weighs only 260g for an energetic stride.</p><ul><li>Mesh upper for breathability</li><li>EVA cushioned sole</li><li>Rubber outsole for grip</li><li>Weight: 260g (size 9)</li></ul>',
            'product_type' => 'variable', 'price' => 1999, 'compare_price' => 2999,
            'weight' => 0.26, 'is_featured' => true, 'is_best_seller' => true, 'is_trending' => true,
            'stock' => 150, 'tags' => ['running', 'lightweight', 'breathable', 'men'],
            'variants' => [
                ['color' => 'black', 'sizes' => ['uk-7','uk-8','uk-9','uk-10','uk-11'], 'price_offset' => 0],
                ['color' => 'blue', 'sizes' => ['uk-7','uk-8','uk-9','uk-10'], 'price_offset' => 0],
            ],
        ],
        [
            'name' => 'Kalenji Kiprun KD500 MTX Trail Running Shoes',
            'sku_prefix' => 'KAL-KD5',
            'brand' => 'kalenji', 'category' => 'men-running-shoes',
            'short_description' => 'Trail running shoes with aggressive grip for off-road',
            'description' => '<p>Built for trail running with aggressive lugs for grip on loose terrain. The reinforced toe cap protects against rocks. Water-repellent upper keeps feet dry on wet trails.</p><ul><li>Aggressive lugs for trail grip</li><li>Reinforced toe cap</li><li>Water-repellent upper</li><li>Midfoot cage for stability</li></ul>',
            'product_type' => 'variable', 'price' => 3499, 'compare_price' => 4999,
            'weight' => 0.32, 'is_featured' => false, 'is_new' => true,
            'stock' => 80, 'tags' => ['running', 'trail', 'waterproof', 'men'],
            'variants' => [
                ['color' => 'black', 'sizes' => ['uk-7','uk-8','uk-9','uk-10'], 'price_offset' => 0],
                ['color' => 'green', 'sizes' => ['uk-8','uk-9','uk-10'], 'price_offset' => 200],
            ],
        ],
        [
            'name' => 'Kalenji Women\'s Running T-Shirt',
            'sku_prefix' => 'KAL-WRT',
            'brand' => 'kalenji', 'category' => 'women-tshirts',
            'short_description' => 'Breathable running t-shirt with reflective details',
            'description' => '<p>Lightweight and breathable running t-shirt designed for women. Features DryPlus technology to wick sweat away from the skin. Reflective elements for visibility during early morning or evening runs.</p><ul><li>DryPlus moisture-wicking fabric</li><li>Reflective details for visibility</li><li>Flat seams to prevent chafing</li><li>Quick-dry material</li></ul>',
            'product_type' => 'variable', 'price' => 599, 'compare_price' => 899,
            'weight' => 0.12, 'is_featured' => false, 'is_best_seller' => true,
            'stock' => 200, 'tags' => ['running', 'women', 'breathable', 'quick-dry'],
            'variants' => [
                ['color' => 'pink', 'sizes' => ['xs','s','m','l','xl'], 'price_offset' => 0],
                ['color' => 'blue', 'sizes' => ['xs','s','m','l'], 'price_offset' => 0],
            ],
        ],

        // ── Football ───────────────────────────────────────────────────
        [
            'name' => 'Kipsta F500 Club Football',
            'sku_prefix' => 'KIP-F500',
            'brand' => 'kipsta', 'category' => 'footballs',
            'short_description' => 'FIFA Quality Pro certified football for competitive play',
            'description' => '<p>The Kipsta F500 is a FIFA Quality Pro certified football designed for competitive matches. Machine-stitched panels ensure consistent flight and durability. Suitable for both natural and artificial grass.</p><ul><li>FIFA Quality Pro certified</li><li>32 machine-stitched panels</li><li>PU outer for durability</li><li>Butyl bladder for air retention</li></ul>',
            'product_type' => 'simple', 'price' => 1299, 'compare_price' => 1799,
            'weight' => 0.43, 'is_featured' => true, 'is_best_seller' => true,
            'stock' => 300, 'tags' => ['football', 'professional', 'unisex'],
        ],
        [
            'name' => 'Kipsta Agility 100 Football Boots',
            'sku_prefix' => 'KIP-AG1',
            'brand' => 'kipsta', 'category' => 'football-shoes',
            'short_description' => 'Lightweight football boots for agile players',
            'description' => '<p>Designed for agile players who rely on speed and quick direction changes. The lightweight synthetic upper provides excellent ball feel while the multi-stud outsole offers traction on firm ground.</p><ul><li>Lightweight synthetic upper</li><li>Multi-stud rubber outsole</li><li>Padded collar for comfort</li><li>Lace-up closure</li></ul>',
            'product_type' => 'variable', 'price' => 1999, 'compare_price' => 2499,
            'weight' => 0.28, 'is_featured' => false, 'is_trending' => true,
            'stock' => 120, 'tags' => ['football', 'lightweight', 'men'],
            'variants' => [
                ['color' => 'black', 'sizes' => ['uk-7','uk-8','uk-9','uk-10','uk-11'], 'price_offset' => 0],
                ['color' => 'white', 'sizes' => ['uk-7','uk-8','uk-9','uk-10'], 'price_offset' => 0],
            ],
        ],

        // ── Cricket ────────────────────────────────────────────────────
        [
            'name' => 'Kookaburra Beast Pro Cricket Bat',
            'sku_prefix' => 'OOK-BP',
            'brand' => 'quechua', 'category' => 'cricket-bats',
            'short_description' => 'Grade 1 English willow cricket bat for professional players',
            'description' => '<p>The Kookaburra Beast Pro is crafted from Grade 1 English willow, hand-selected for optimal performance. Features a mid-to-low sweet spot ideal for front-foot players. Comes with a protective bat cover.</p><ul><li>Grade 1 English willow</li><li>Mid-to-low sweet spot</li><li>39mm edge profile</li><li>Short handle, standard grip</li><li>Includes bat cover</li></ul>',
            'product_type' => 'simple', 'price' => 8999, 'compare_price' => 12999,
            'weight' => 1.15, 'is_featured' => true, 'is_new' => true,
            'stock' => 40, 'tags' => ['cricket', 'professional', 'men'],
        ],
        [
            'name' => 'SG Nexus Rz Xtreme Cricket Bat',
            'sku_prefix' => 'SG-NRZ',
            'brand' => 'quechua', 'category' => 'cricket-bats',
            'short_description' => 'Kashmir willow bat for intermediate players',
            'description' => '<p>The SG Nexus Rz Xtreme is crafted from premium Kashmir willow, ideal for intermediate-level cricketers. Features a large sweet spot and excellent balance for powerful stroke play.</p><ul><li>Premium Kashmir willow</li><li>Large sweet spot</li><li>Full cane handle</li><li>Comes with bat cover</li></ul>',
            'product_type' => 'simple', 'price' => 2999, 'compare_price' => 4499,
            'weight' => 1.25, 'is_featured' => false, 'is_best_seller' => true,
            'stock' => 75, 'tags' => ['cricket', 'beginner', 'men'],
        ],

        // ── Yoga & Fitness ─────────────────────────────────────────────
        [
            'name' => 'Domyos Yoga Mat 8mm Premium',
            'sku_prefix' => 'DOM-YM8',
            'brand' => 'domyos', 'category' => 'yoga-mats',
            'short_description' => 'Premium 8mm yoga mat with alignment lines',
            'description' => '<p>The Domyos Yoga Mat 8mm Premium offers superior comfort and grip for yoga, Pilates, and floor exercises. Features alignment lines to help position your body correctly. Non-slip surface on both sides.</p><ul><li>8mm thick for joint comfort</li><li>Alignment lines for proper positioning</li><li>Non-slip TPE material</li><li>Eco-friendly, free from PVC and latex</li><li>Dimensions: 183cm x 68cm</li></ul>',
            'product_type' => 'variable', 'price' => 1499, 'compare_price' => 1999,
            'weight' => 1.2, 'is_featured' => true, 'is_best_seller' => true, 'is_trending' => true,
            'stock' => 250, 'tags' => ['yoga', 'fitness', 'eco-friendly', 'women', 'unisex'],
            'variants' => [
                ['color' => 'blue', 'sizes' => [], 'price_offset' => 0],
                ['color' => 'purple', 'sizes' => [], 'price_offset' => 0],
                ['color' => 'green', 'sizes' => [], 'price_offset' => 100],
            ],
        ],
        [
            'name' => 'Domyos Adjustable Dumbbell Set 20kg',
            'sku_prefix' => 'DOM-DB20',
            'brand' => 'domyos', 'category' => 'dumbbells',
            'short_description' => 'Adjustable dumbbell set from 2kg to 20kg',
            'description' => '<p>Complete adjustable dumbbell set for home workouts. Quick-lock mechanism allows weight changes in seconds. Chrome-plated steel bars with rubber-coated weight plates for floor protection.</p><ul><li>Weight range: 2kg to 20kg (pair)</li><li>Quick-lock mechanism</li><li>Chrome-plated steel bars</li><li>Rubber-coated weight plates</li><li>Includes storage tray</li></ul>',
            'product_type' => 'simple', 'price' => 4999, 'compare_price' => 6999,
            'weight' => 20.0, 'is_featured' => false, 'is_best_seller' => true,
            'stock' => 60, 'tags' => ['gym-fitness', 'strength', 'men', 'women'],
        ],
        [
            'name' => 'Domyos Resistance Bands Set (5 Pack)',
            'sku_prefix' => 'DOM-RB5',
            'brand' => 'domyos', 'category' => 'resistance-bands',
            'short_description' => 'Set of 5 resistance bands with different tension levels',
            'description' => '<p>Five resistance bands in different tension levels for progressive training. Perfect for warm-ups, rehabilitation, and strength training. Includes a mesh carry bag and exercise guide.</p><ul><li>5 bands: 5kg, 10kg, 15kg, 20kg, 25kg</li><li>Natural latex for durability</li><li>Color-coded by tension</li><li>Includes carry bag and guide</li></ul>',
            'product_type' => 'simple', 'price' => 699, 'compare_price' => 999,
            'weight' => 0.5, 'is_featured' => false, 'is_trending' => true,
            'stock' => 400, 'tags' => ['gym-fitness', 'lightweight', 'women', 'men'],
        ],

        // ── Badminton ──────────────────────────────────────────────────
        [
            'name' => 'Artengo BR830 Badminton Racket',
            'sku_prefix' => 'ART-BR83',
            'brand' => 'artengo', 'category' => 'badminton-rackets',
            'short_description' => 'Lightweight graphite badminton racket for intermediate players',
            'description' => '<p>The Artengo BR830 is a lightweight graphite racket designed for intermediate players. The isometric head shape provides a larger sweet spot. Pre-strung at 24lbs for a good balance of power and control.</p><ul><li>Full graphite frame</li><li>Isometric head shape</li><li>Pre-strung at 24lbs</li><li>Weight: 85g (unstrung)</li><li>Includes full-length cover</li></ul>',
            'product_type' => 'simple', 'price' => 2499, 'compare_price' => 3499,
            'weight' => 0.085, 'is_featured' => false, 'is_best_seller' => true,
            'stock' => 100, 'tags' => ['badminton', 'lightweight', 'men', 'women'],
        ],
        [
            'name' => 'Artengo BS530 Shuttlecocks (Pack of 12)',
            'sku_prefix' => 'ART-BS5',
            'brand' => 'artengo', 'category' => 'shuttlecocks',
            'short_description' => 'Nylon shuttlecocks for recreational and training play',
            'description' => '<p>Durable nylon shuttlecocks designed for recreational play and training sessions. Consistent flight path and good durability. Pack of 12 for extended practice sessions.</p><ul><li>Nylon skirt for durability</li><li>Cork base for consistent flight</li><li>Pack of 12 shuttlecocks</li><li>Suitable for indoor play</li></ul>',
            'product_type' => 'simple', 'price' => 399, 'compare_price' => 599,
            'weight' => 0.2, 'is_featured' => false, 'is_best_seller' => true,
            'stock' => 500, 'tags' => ['badminton', 'beginner', 'unisex'],
        ],

        // ── Cycling ────────────────────────────────────────────────────
        [
            'name' => 'BTwin Riverside 120 City Bicycle',
            'sku_prefix' => 'BT-RI12',
            'brand' => 'btwin', 'category' => 'city-bikes',
            'short_description' => 'Comfortable city bicycle for daily commutes',
            'description' => '<p>The BTwin Riverside 120 is designed for comfortable city commuting. Features a lightweight aluminum frame, 7-speed Shimano gearing, and puncture-resistant tires. Upright riding position for comfort and visibility.</p><ul><li>Lightweight aluminum frame</li><li>7-speed Shimano gearing</li><li>Puncture-resistant tires</li><li>Front and rear lights</li><li>Rear rack for panniers</li></ul>',
            'product_type' => 'simple', 'price' => 14999, 'compare_price' => 18999,
            'weight' => 14.5, 'is_featured' => true, 'is_new' => true,
            'stock' => 30, 'tags' => ['cycling', 'lightweight', 'men', 'women'],
        ],
        [
            'name' => 'BTwin Rockrider ST100 Mountain Bike',
            'sku_prefix' => 'BT-ST10',
            'brand' => 'rockrider', 'category' => 'mountain-bikes',
            'short_description' => 'Entry-level mountain bike for trails and off-road',
            'description' => '<p>The Rockrider ST100 is built for beginner mountain bikers who want to explore off-road trails. Features a steel frame, 21-speed Shimano gearing, and front suspension for comfort on rough terrain.</p><ul><li>Steel frame for durability</li><li>21-speed Shimano gearing</li><li>Front suspension fork</li><li>26-inch wheels</li><li>Mechanical disc brakes</li></ul>',
            'product_type' => 'simple', 'price' => 19999, 'compare_price' => 24999,
            'weight' => 16.0, 'is_featured' => false, 'is_best_seller' => true,
            'stock' => 20, 'tags' => ['cycling', 'professional', 'men'],
        ],

        // ── Swimming ───────────────────────────────────────────────────
        [
            'name' => 'Nabaiji Omni 1 Swim Goggles',
            'sku_prefix' => 'NAB-O1G',
            'brand' => 'nabaiji', 'category' => 'swim-goggles',
            'short_description' => 'Comfortable swim goggles with anti-fog coating',
            'description' => '<p>The Nabaiji Omni 1 swim goggles feature a comfortable silicone seal and anti-fog coated polycarbonate lenses. Adjustable double strap for a secure fit. UV protection for outdoor swimming.</p><ul><li>Anti-fog coated lenses</li><li>UV protection</li><li>Comfortable silicone seal</li><li>Adjustable double strap</li><li>Polycarbonate lenses</li></ul>',
            'product_type' => 'variable', 'price' => 399, 'compare_price' => 599,
            'weight' => 0.08, 'is_featured' => false, 'is_best_seller' => true,
            'stock' => 600, 'tags' => ['swimming', 'lightweight', 'men', 'women', 'kids'],
            'variants' => [
                ['color' => 'black', 'sizes' => [], 'price_offset' => 0],
                ['color' => 'blue', 'sizes' => [], 'price_offset' => 0],
            ],
        ],
        [
            'name' => 'Nabaiji Swim Jammer (Men)',
            'sku_prefix' => 'NAB-SJM',
            'brand' => 'nabaiji', 'category' => 'swimwear-men',
            'short_description' => 'Chlorine-resistant swim jammer for training',
            'description' => '<p>The Nabaiji Swim Jammer is designed for regular swimmers who train in chlorinated pools. Made from chlorine-resistant fabric that maintains its shape and color. Flat seams for comfort.</p><ul><li>Chlorine-resistant MaxLife fabric</li><li>Flat seams for comfort</li><li>Internal drawstring</li><li>UPF 50+ sun protection</li></ul>',
            'product_type' => 'variable', 'price' => 799, 'compare_price' => 1199,
            'weight' => 0.15, 'is_featured' => false, 'is_new' => true,
            'stock' => 200, 'tags' => ['swimming', 'men', 'quick-dry'],
            'variants' => [
                ['color' => 'black', 'sizes' => ['xs','s','m','l','xl'], 'price_offset' => 0],
                ['color' => 'blue', 'sizes' => ['s','m','l','xl'], 'price_offset' => 0],
            ],
        ],

        // ── Camping & Hiking ───────────────────────────────────────────
        [
            'name' => 'Quechua MH100 Hiking Backpack 20L',
            'sku_prefix' => 'QUE-MH1',
            'brand' => 'quechua', 'category' => 'hiking-backpacks',
            'short_description' => 'Lightweight 20L hiking backpack for day hikes',
            'description' => '<p>The Quechua MH100 is a lightweight 20L backpack designed for day hikes. Features a padded back panel, adjustable sternum strap, and multiple compartments. Rain cover included.</p><ul><li>20L capacity</li><li>Padded back panel</li><li>Adjustable sternum strap</li><li>Rain cover included</li><li>Weight: 380g</li></ul>',
            'product_type' => 'simple', 'price' => 1299, 'compare_price' => 1799,
            'weight' => 0.38, 'is_featured' => false, 'is_best_seller' => true,
            'stock' => 180, 'tags' => ['hiking', 'lightweight', 'waterproof', 'men', 'women'],
        ],
        [
            'name' => 'Quechua MH500 Waterproof Hiking Jacket',
            'sku_prefix' => 'QUE-MH5',
            'brand' => 'quechua', 'category' => 'men-sports-jackets',
            'short_description' => 'Waterproof and breathable hiking jacket',
            'description' => '<p>The Quechua MH500 is a waterproof hiking jacket with a 5000mm waterproof rating. Features sealed seams, adjustable hood, and pit zips for ventilation. Packs into its own pocket for easy carrying.</p><ul><li>5000mm waterproof rating</li><li>Sealed seams</li><li>Adjustable hood</li><li>Pit zips for ventilation</li><li>Packs into own pocket</li></ul>',
            'product_type' => 'variable', 'price' => 3999, 'compare_price' => 5999,
            'weight' => 0.45, 'is_featured' => true, 'is_trending' => true,
            'stock' => 90, 'tags' => ['hiking', 'waterproof', 'lightweight', 'men'],
            'variants' => [
                ['color' => 'green', 'sizes' => ['s','m','l','xl'], 'price_offset' => 0],
                ['color' => 'black', 'sizes' => ['s','m','l','xl','xxl'], 'price_offset' => 0],
            ],
        ],
        [
            'name' => 'Quechua Forclaz Trek 100 Sleeping Bag',
            'sku_prefix' => 'QUE-FT1',
            'brand' => 'forclaz', 'category' => 'sleeping-bags',
            'short_description' => 'Comfortable 3-season sleeping bag for trekking',
            'description' => '<p>The Forclaz Trek 100 sleeping bag is designed for 3-season trekking. Comfortable down to 0 degrees C with a mummy shape for warmth efficiency. Weighs only 900g and packs down to 3L.</p><ul><li>Comfort rating: 0 degrees C</li><li>Mummy shape for warmth</li><li>Weight: 900g</li><li>Pack volume: 3L</li><li>YKK zipper</li></ul>',
            'product_type' => 'variable', 'price' => 3499, 'compare_price' => 4999,
            'weight' => 0.9, 'is_featured' => false, 'is_new' => true,
            'stock' => 60, 'tags' => ['camping', 'hiking', 'lightweight', 'men', 'women'],
            'variants' => [
                ['color' => 'blue', 'sizes' => [], 'price_offset' => 0],
                ['color' => 'orange', 'sizes' => [], 'price_offset' => 0],
            ],
        ],

        // ── Tennis ─────────────────────────────────────────────────────
        [
            'name' => 'Artengo TR530 Tennis Racket',
            'sku_prefix' => 'ART-TR5',
            'brand' => 'artengo', 'category' => 'tennis-rackets',
            'short_description' => 'Lightweight tennis racket for recreational players',
            'description' => '<p>The Artengo TR530 is designed for recreational tennis players. The oversized head provides a larger sweet spot, while the lightweight frame allows for easy maneuverability. Pre-strung at 23lbs.</p><ul><li>Oversized head (105 sq in)</li><li>Aluminum frame</li><li>Pre-strung at 23lbs</li><li>Weight: 270g</li><li>Includes racket cover</li></ul>',
            'product_type' => 'simple', 'price' => 1799, 'compare_price' => 2499,
            'weight' => 0.27, 'is_featured' => false, 'is_best_seller' => true,
            'stock' => 85, 'tags' => ['tennis', 'lightweight', 'beginner', 'men', 'women'],
        ],

        // ── Winter / Jackets ───────────────────────────────────────────
        [
            'name' => 'Quechua SH500 Warm Padded Jacket',
            'sku_prefix' => 'QUE-SH5',
            'brand' => 'quechua', 'category' => 'men-padded-down-jackets',
            'short_description' => 'Lightweight padded jacket for cold weather',
            'description' => '<p>The SH500 Warm Padded Jacket provides excellent insulation in cold weather. Features synthetic filling that retains warmth even when wet. Water-repellent outer shell for light rain protection.</p><ul><li>Synthetic insulated filling</li><li>Water-repellent outer shell</li><li>Elasticated cuffs and hem</li><li>Two zip pockets</li><li>Weight: 350g</li></ul>',
            'product_type' => 'variable', 'price' => 2999, 'compare_price' => 4499,
            'weight' => 0.35, 'is_featured' => true, 'is_trending' => true,
            'stock' => 110, 'tags' => ['winter', 'waterproof', 'lightweight', 'men'],
            'variants' => [
                ['color' => 'black', 'sizes' => ['s','m','l','xl'], 'price_offset' => 0],
                ['color' => 'navy-blue', 'sizes' => ['s','m','l','xl'], 'price_offset' => 0],
                ['color' => 'grey', 'sizes' => ['m','l','xl'], 'price_offset' => 0],
            ],
        ],

        // ── Kids ───────────────────────────────────────────────────────
        [
            'name' => 'Kipsta Kids Football Kit (Jersey + Shorts)',
            'sku_prefix' => 'KIP-KFK',
            'brand' => 'kipsta', 'category' => 'kids-jersey-kits',
            'short_description' => 'Complete football kit for kids',
            'description' => '<p>Get your little athlete match-ready with the Kipsta Kids Football Kit. Includes a breathable jersey and comfortable shorts with an elastic waistband. Available in team colors.</p><ul><li>Breathable polyester jersey</li><li>Comfortable elastic waistband shorts</li><li>Sublimated team colors</li><li>Sizes: 4-5 years to 14-15 years</li></ul>',
            'product_type' => 'variable', 'price' => 799, 'compare_price' => 1199,
            'weight' => 0.3, 'is_featured' => false, 'is_best_seller' => true,
            'stock' => 200, 'tags' => ['football', 'kids', 'breathable'],
            'variants' => [
                ['color' => 'blue', 'sizes' => ['s','m','l','xl'], 'price_offset' => 0],
                ['color' => 'red', 'sizes' => ['s','m','l'], 'price_offset' => 0],
            ],
        ],
        [
            'name' => 'Kalenji Kids Running Shoes',
            'sku_prefix' => 'KAL-KRS',
            'brand' => 'kalenji', 'category' => 'kids-running-shoes',
            'short_description' => 'Lightweight running shoes for active kids',
            'description' => '<p>Keep your kids active with the Kalenji Kids Running Shoes. Lightweight and breathable with a cushioned sole for comfort during play. Easy slip-on design with adjustable velcro strap.</p><ul><li>Lightweight mesh upper</li><li>Cushioned EVA sole</li><li>Velcro strap closure</li><li>Reflective details for visibility</li></ul>',
            'product_type' => 'variable', 'price' => 999, 'compare_price' => 1499,
            'weight' => 0.18, 'is_featured' => false, 'is_trending' => true,
            'stock' => 250, 'tags' => ['running', 'kids', 'lightweight'],
            'variants' => [
                ['color' => 'red', 'sizes' => ['uk-4','uk-5','uk-6'], 'price_offset' => 0],
                ['color' => 'blue', 'sizes' => ['uk-4','uk-5','uk-6'], 'price_offset' => 0],
            ],
        ],

        // ── Gym Accessories ────────────────────────────────────────────
        [
            'name' => 'Domyos Gym Gloves',
            'sku_prefix' => 'DOM-GG',
            'brand' => 'domyos', 'category' => 'gym-gloves',
            'short_description' => 'Padded gym gloves for weight training',
            'description' => '<p>Protect your hands during weight training with Domyos Gym Gloves. Features padded palms for comfort and a wrist strap for support. Breathable mesh back keeps hands cool.</p><ul><li>Padded leather palms</li><li>Wrist support strap</li><li>Breathable mesh back</li><li>Velcro closure</li></ul>',
            'product_type' => 'variable', 'price' => 499, 'compare_price' => 799,
            'weight' => 0.1, 'is_featured' => false, 'is_best_seller' => true,
            'stock' => 300, 'tags' => ['gym-fitness', 'men', 'women'],
            'variants' => [
                ['color' => 'black', 'sizes' => ['s','m','l'], 'price_offset' => 0],
                ['color' => 'grey', 'sizes' => ['m','l','xl'], 'price_offset' => 0],
            ],
        ],
        [
            'name' => 'Domyos Shaker Bottle 700ml',
            'sku_prefix' => 'DOM-SB7',
            'brand' => 'domyos', 'category' => 'shaker-bottles',
            'short_description' => 'BPA-free shaker bottle for protein shakes',
            'description' => '<p>Mix your protein shakes on the go with the Domyos Shaker Bottle. BPA-free construction with a leak-proof lid and mixing mesh for smooth shakes. 700ml capacity with measurement markings.</p><ul><li>700ml capacity</li><li>BPA-free Tritan material</li><li>Leak-proof lid</li><li>Mixing mesh for smooth shakes</li><li>Measurement markings</li></ul>',
            'product_type' => 'simple', 'price' => 299, 'compare_price' => 449,
            'weight' => 0.15, 'is_featured' => false, 'is_best_seller' => true,
            'stock' => 500, 'tags' => ['gym-fitness', 'eco-friendly', 'men', 'women'],
        ],

        // ── Water Bottles ──────────────────────────────────────────────
        [
            'name' => 'Quechua Insulated Water Bottle 750ml',
            'sku_prefix' => 'QUE-IWB',
            'brand' => 'quechua', 'category' => 'water-bottles',
            'short_description' => 'Double-wall insulated water bottle',
            'description' => '<p>Keep your drinks cold for 24 hours or hot for 12 hours with the Quechua Insulated Water Bottle. Double-wall stainless steel construction with a leak-proof cap. Fits standard bike bottle cages.</p><ul><li>750ml capacity</li><li>Double-wall stainless steel</li><li>Cold 24h / Hot 12h</li><li>Leak-proof cap</li><li>Fits bike bottle cages</li></ul>',
            'product_type' => 'simple', 'price' => 699, 'compare_price' => 999,
            'weight' => 0.35, 'is_featured' => false, 'is_new' => true,
            'stock' => 350, 'tags' => ['eco-friendly', 'cycling', 'hiking', 'unisex'],
        ],

        // ── Trekking Poles ─────────────────────────────────────────────
        [
            'name' => 'Quechua MH500 Trekking Poles (Pair)',
            'sku_prefix' => 'QUE-TP5',
            'brand' => 'quechua', 'category' => 'trekking-poles',
            'short_description' => 'Adjustable aluminum trekking poles',
            'description' => '<p>Lightweight and adjustable trekking poles for hiking and trekking. Quick-lock mechanism for easy height adjustment. Cork grips for comfort and sweat absorption. Includes rubber tips and mud baskets.</p><ul><li>Adjustable: 65-135cm</li><li>Aluminum shaft</li><li>Quick-lock mechanism</li><li>Cork grips</li><li>Weight: 240g per pole</li></ul>',
            'product_type' => 'simple', 'price' => 1499, 'compare_price' => 2199,
            'weight' => 0.48, 'is_featured' => false, 'is_new' => true,
            'stock' => 120, 'tags' => ['hiking', 'lightweight', 'men', 'women'],
        ],

        // ── Waterproof Rain Pants ──────────────────────────────────────
        [
            'name' => 'Quechua Waterproof Rain Pants',
            'sku_prefix' => 'QUE-WRP',
            'brand' => 'quechua', 'category' => 'men-waterproof-rain-pants',
            'short_description' => 'Lightweight waterproof over-trousers',
            'description' => '<p>Stay dry in heavy rain with the Quechua Waterproof Rain Pants. 2000mm waterproof rating with taped seams. Lightweight and packable, they fold into their own pocket. Elasticated waist with drawstring.</p><ul><li>2000mm waterproof rating</li><li>Taped seams</li><li>Elasticated waist</li><li>Packs into own pocket</li><li>Side zips for easy on/off</li></ul>',
            'product_type' => 'variable', 'price' => 1299, 'compare_price' => 1799,
            'weight' => 0.2, 'is_featured' => false, 'is_trending' => true,
            'stock' => 150, 'tags' => ['waterproof', 'monsoon', 'men'],
            'variants' => [
                ['color' => 'black', 'sizes' => ['s','m','l','xl'], 'price_offset' => 0],
                ['color' => 'grey', 'sizes' => ['m','l','xl'], 'price_offset' => 0],
            ],
        ],

        // ── Compression Wear ───────────────────────────────────────────
        [
            'name' => 'Kalenji Men\'s Compression Tights',
            'sku_prefix' => 'KAL-MCT',
            'brand' => 'kalenji', 'category' => 'men-tights-compression',
            'short_description' => 'Graduated compression running tights',
            'description' => '<p>Improve your running recovery with Kalenji Compression Tights. Graduated compression promotes blood flow and reduces muscle fatigue. Moisture-wicking fabric keeps you dry and comfortable.</p><ul><li>Graduated compression</li><li>Moisture-wicking fabric</li><li>Flat-lock seams</li><li>High waistband</li><li>Reflective details</li></ul>',
            'product_type' => 'variable', 'price' => 1299, 'compare_price' => 1799,
            'weight' => 0.18, 'is_featured' => false, 'is_new' => true,
            'stock' => 100, 'tags' => ['running', 'compression', 'men'],
            'variants' => [
                ['color' => 'black', 'sizes' => ['s','m','l','xl'], 'price_offset' => 0],
                ['color' => 'navy-blue', 'sizes' => ['m','l','xl'], 'price_offset' => 0],
            ],
        ],

        // ── Running T-shirt Men ────────────────────────────────────────
        [
            'name' => 'Kalenji Men\'s Dry Running T-Shirt',
            'sku_prefix' => 'KAL-MRT',
            'brand' => 'kalenji', 'category' => 'men-cotton-tshirt',
            'short_description' => 'Moisture-wicking running t-shirt',
            'description' => '<p>Stay comfortable during your runs with the Kalenji Dry Running T-Shirt. DryPlus technology wicks sweat away from the skin. Lightweight and breathable mesh panels under the arms.</p><ul><li>DryPlus moisture-wicking</li><li>Breathable mesh panels</li><li>Flat seams</li><li>Reflective logo</li><li>Weight: 110g</li></ul>',
            'product_type' => 'variable', 'price' => 499, 'compare_price' => 699,
            'weight' => 0.11, 'is_featured' => false, 'is_best_seller' => true,
            'stock' => 400, 'tags' => ['running', 'men', 'breathable', 'quick-dry'],
            'variants' => [
                ['color' => 'black', 'sizes' => ['s','m','l','xl','xxl'], 'price_offset' => 0],
                ['color' => 'grey', 'sizes' => ['s','m','l','xl'], 'price_offset' => 0],
                ['color' => 'blue', 'sizes' => ['s','m','l','xl'], 'price_offset' => 0],
            ],
        ],

        // ── Polo T-shirt ───────────────────────────────────────────────
        [
            'name' => 'Kalenji Men\'s Sport Polo T-Shirt',
            'sku_prefix' => 'KAL-MPT',
            'brand' => 'kalenji', 'category' => 'men-polo-tshirt',
            'short_description' => 'Versatile sport polo for training and casual wear',
            'description' => '<p>A versatile polo that works both on the court and off it. Made from quick-dry fabric with a classic polo collar. Perfect for tennis, badminton, or casual outings.</p><ul><li>Quick-dry polyester</li><li>Classic polo collar</li><li>Side vents for mobility</li><li>UPF 25 sun protection</li></ul>',
            'product_type' => 'variable', 'price' => 699, 'compare_price' => 999,
            'weight' => 0.15, 'is_featured' => false, 'is_best_seller' => true,
            'stock' => 250, 'tags' => ['men', 'breathable', 'uv-protection'],
            'variants' => [
                ['color' => 'white', 'sizes' => ['s','m','l','xl','xxl'], 'price_offset' => 0],
                ['color' => 'navy-blue', 'sizes' => ['s','m','l','xl'], 'price_offset' => 0],
            ],
        ],

        // ── Men Shorts ─────────────────────────────────────────────────
        [
            'name' => 'Kalenji Men\'s Running Shorts',
            'sku_prefix' => 'KAL-MRS',
            'brand' => 'kalenji', 'category' => 'men-shorts',
            'short_description' => 'Lightweight running shorts with inner brief',
            'description' => '<p>Lightweight and comfortable running shorts with a built-in inner brief. Features a zip pocket for keys and a reflective logo for visibility. Elasticated waist with drawstring.</p><ul><li>Lightweight woven fabric</li><li>Built-in inner brief</li><li>Zip back pocket</li><li>Elasticated waist</li><li>Reflective logo</li></ul>',
            'product_type' => 'variable', 'price' => 499, 'compare_price' => 699,
            'weight' => 0.1, 'is_featured' => false, 'is_best_seller' => true,
            'stock' => 350, 'tags' => ['running', 'men', 'lightweight'],
            'variants' => [
                ['color' => 'black', 'sizes' => ['s','m','l','xl','xxl'], 'price_offset' => 0],
                ['color' => 'grey', 'sizes' => ['s','m','l','xl'], 'price_offset' => 0],
                ['color' => 'navy-blue', 'sizes' => ['s','m','l','xl'], 'price_offset' => 0],
            ],
        ],

        // ── Track Pants ────────────────────────────────────────────────
        [
            'name' => 'Domyos Men\'s Fitness Track Pants',
            'sku_prefix' => 'DOM-MFP',
            'brand' => 'domyos', 'category' => 'men-track-pants-joggers',
            'short_description' => 'Comfortable track pants for gym and everyday wear',
            'description' => '<p>The Domyos Fitness Track Pants are perfect for gym sessions and everyday wear. Made from soft, stretchy fabric with zip pockets for security. Tapered fit for a modern look.</p><ul><li>Soft stretchy fabric</li><li>Tapered fit</li><li>Zip pockets</li><li>Elasticated waist</li><li>Material: 85% polyester, 15% elastane</li></ul>',
            'product_type' => 'variable', 'price' => 999, 'compare_price' => 1499,
            'weight' => 0.3, 'is_featured' => false, 'is_best_seller' => true,
            'stock' => 200, 'tags' => ['gym-fitness', 'men', 'breathable'],
            'variants' => [
                ['color' => 'black', 'sizes' => ['s','m','l','xl','xxl'], 'price_offset' => 0],
                ['color' => 'grey', 'sizes' => ['s','m','l','xl'], 'price_offset' => 0],
            ],
        ],

        // ── Women Leggings ─────────────────────────────────────────────
        [
            'name' => 'Domyos Women\'s Fitness Leggings',
            'sku_prefix' => 'DOM-WFL',
            'brand' => 'domyos', 'category' => 'women-leggings',
            'short_description' => 'High-waist fitness leggings with pocket',
            'description' => '<p>The Domyos Fitness Leggings are designed for women who want comfort and style during workouts. High-waist design with a hidden pocket. Made from moisture-wicking fabric with 4-way stretch.</p><ul><li>High-waist design</li><li>Hidden pocket</li><li>4-way stretch fabric</li><li>Moisture-wicking</li><li>Flat-lock seams</li></ul>',
            'product_type' => 'variable', 'price' => 899, 'compare_price' => 1299,
            'weight' => 0.2, 'is_featured' => true, 'is_best_seller' => true,
            'stock' => 300, 'tags' => ['gym-fitness', 'women', 'breathable'],
            'variants' => [
                ['color' => 'black', 'sizes' => ['xs','s','m','l','xl'], 'price_offset' => 0],
                ['color' => 'grey', 'sizes' => ['xs','s','m','l'], 'price_offset' => 0],
                ['color' => 'pink', 'sizes' => ['s','m','l'], 'price_offset' => 0],
            ],
        ],

        // ── Women Tank Top ─────────────────────────────────────────────
        [
            'name' => 'Domyos Women\'s Fitness Tank Top',
            'sku_prefix' => 'DOM-WFT',
            'brand' => 'domyos', 'category' => 'women-tank-tops',
            'short_description' => 'Lightweight fitness tank top',
            'description' => '<p>Stay cool during workouts with the Domyos Fitness Tank Top. Lightweight and breathable with a loose fit for maximum airflow. Racerback design allows full range of motion.</p><ul><li>Lightweight breathable fabric</li><li>Racerback design</li><li>Loose fit</li><li>Quick-dry material</li></ul>',
            'product_type' => 'variable', 'price' => 399, 'compare_price' => 599,
            'weight' => 0.08, 'is_featured' => false, 'is_best_seller' => true,
            'stock' => 350, 'tags' => ['gym-fitness', 'women', 'breathable', 'quick-dry'],
            'variants' => [
                ['color' => 'black', 'sizes' => ['xs','s','m','l','xl'], 'price_offset' => 0],
                ['color' => 'white', 'sizes' => ['xs','s','m','l'], 'price_offset' => 0],
            ],
        ],

        // ── Sports Bra ─────────────────────────────────────────────────
        [
            'name' => 'Domyos Medium Support Sports Bra',
            'sku_prefix' => 'DOM-MSB',
            'brand' => 'domyos', 'category' => 'women-sports-bra',
            'short_description' => 'Medium support sports bra for training',
            'description' => '<p>The Domyos Medium Support Sports Bra provides comfortable support during medium-intensity activities like gym training, cycling, and hiking. Breathable mesh zones keep you cool.</p><ul><li>Medium support</li><li>Breathable mesh zones</li><li>Removable padding</li><li>Wide elastic band</li><li>Hook-and-eye closure</li></ul>',
            'product_type' => 'variable', 'price' => 599, 'compare_price' => 899,
            'weight' => 0.1, 'is_featured' => false, 'is_best_seller' => true,
            'stock' => 250, 'tags' => ['gym-fitness', 'women', 'breathable'],
            'variants' => [
                ['color' => 'black', 'sizes' => ['xs','s','m','l','xl'], 'price_offset' => 0],
                ['color' => 'pink', 'sizes' => ['xs','s','m','l'], 'price_offset' => 0],
            ],
        ],

        // ── Swim Goggles Kids ──────────────────────────────────────────
        [
            'name' => 'Nabaiji Kids Swim Goggles',
            'sku_prefix' => 'NAB-KSG',
            'brand' => 'nabaiji', 'category' => 'swim-goggles',
            'short_description' => 'Comfortable swim goggles designed for kids',
            'description' => '<p>Designed specifically for kids with smaller faces. The Nabaiji Kids Swim Goggles feature soft silicone seals for comfort and anti-fog lenses for clear vision. Fun colors that kids love.</p><ul><li>Soft silicone seal</li><li>Anti-fog lenses</li><li>Adjustable strap</li><li>UV protection</li><li>Kid-friendly colors</li></ul>',
            'product_type' => 'variable', 'price' => 299, 'compare_price' => 449,
            'weight' => 0.05, 'is_featured' => false, 'is_best_seller' => true,
            'stock' => 400, 'tags' => ['swimming', 'kids', 'lightweight'],
            'variants' => [
                ['color' => 'blue', 'sizes' => [], 'price_offset' => 0],
                ['color' => 'pink', 'sizes' => [], 'price_offset' => 0],
            ],
        ],

        // ── Jump Rope ──────────────────────────────────────────────────
        [
            'name' => 'Domyos Skipping Rope',
            'sku_prefix' => 'DOM-SR',
            'brand' => 'domyos', 'category' => 'jump-ropes',
            'short_description' => 'Adjustable skipping rope for cardio training',
            'description' => '<p>The Domyos Skipping Rope is perfect for cardio warm-ups and conditioning. Adjustable length, comfortable foam grips, and a smooth ball bearing system for fast rotation.</p><ul><li>Adjustable length (up to 3m)</li><li>Foam grips</li><li>Ball bearing system</li><li>PVC cord</li></ul>',
            'product_type' => 'simple', 'price' => 199, 'compare_price' => 299,
            'weight' => 0.12, 'is_featured' => false, 'is_best_seller' => true,
            'stock' => 600, 'tags' => ['gym-fitness', 'lightweight', 'unisex'],
        ],

        // ── Foam Roller ────────────────────────────────────────────────
        [
            'name' => 'Domyos Foam Roller 90cm',
            'sku_prefix' => 'DOM-FR9',
            'brand' => 'domyos', 'category' => 'foam-rollers',
            'short_description' => 'High-density foam roller for muscle recovery',
            'description' => '<p>The Domyos Foam Roller helps relieve muscle tension and improve flexibility. High-density EVA foam for deep tissue massage. Perfect for post-workout recovery and yoga.</p><ul><li>90cm length</li><li>High-density EVA foam</li><li>Textured surface</li><li>Lightweight and portable</li></ul>',
            'product_type' => 'simple', 'price' => 799, 'compare_price' => 1199,
            'weight' => 0.8, 'is_featured' => false, 'is_new' => true,
            'stock' => 180, 'tags' => ['gym-fitness', 'yoga', 'unisex'],
        ],

        // ── Table Tennis ───────────────────────────────────────────────
        [
            'name' => 'Artengo 800 Table Tennis Bat',
            'sku_prefix' => 'ART-800',
            'brand' => 'artengo', 'category' => 'tt-bats',
            'short_description' => 'Intermediate table tennis bat with inverted rubber',
            'description' => '<p>The Artengo 800 is an intermediate-level table tennis bat with inverted rubber for spin and control. 5-ply wood blade for good feedback. Suitable for players training 2-3 times per week.</p><ul><li>Inverted rubber for spin</li><li>5-ply wood blade</li><li>ITTF approved rubber</li><li>Includes protective case</li></ul>',
            'product_type' => 'simple', 'price' => 1299, 'compare_price' => 1799,
            'weight' => 0.18, 'is_featured' => false, 'is_best_seller' => true,
            'stock' => 120, 'tags' => ['tennis', 'intermediate', 'men', 'women'],
        ],

        // ── Socks ──────────────────────────────────────────────────────
        [
            'name' => 'Kalenji Running Socks (3 Pack)',
            'sku_prefix' => 'KAL-RS3',
            'brand' => 'kalenji', 'category' => 'men-socks',
            'short_description' => 'Cushioned running socks with arch support',
            'description' => '<p>Three pairs of cushioned running socks designed to prevent blisters. Features arch support, breathable mesh zones, and reinforced heel and toe areas. Available in multiple sizes.</p><ul><li>3 pairs per pack</li><li>Cushioned sole</li><li>Arch support</li><li>Breathable mesh zones</li><li>Reinforced heel and toe</li></ul>',
            'product_type' => 'variable', 'price' => 399, 'compare_price' => 599,
            'weight' => 0.15, 'is_featured' => false, 'is_best_seller' => true,
            'stock' => 500, 'tags' => ['running', 'men', 'breathable'],
            'variants' => [
                ['color' => 'white', 'sizes' => ['uk-6','uk-7','uk-8','uk-9','uk-10'], 'price_offset' => 0],
                ['color' => 'black', 'sizes' => ['uk-6','uk-7','uk-8','uk-9'], 'price_offset' => 0],
            ],
        ],

        // ── Additional products to round out the catalog ────────────────
        [
            'name' => 'Quechua Camping Tent 2 Seconds XL',
            'sku_prefix' => 'QUE-T2S',
            'brand' => 'quechua', 'category' => 'tents',
            'short_description' => 'Instant pop-up tent for 2 people',
            'description' => '<p>Set up your tent in seconds with the Quechua 2 Seconds XL. Pop-up design opens instantly, and the waterproof flysheet keeps you dry. Sleeps 2 people comfortably with standing height in the porch.</p><ul><li>Instant pop-up setup</li><li>Waterproof flysheet</li><li>Sleeps 2 people</li><li>Standing height porch</li><li>Weight: 3.8kg</li></ul>',
            'product_type' => 'simple', 'price' => 5999, 'compare_price' => 7999,
            'weight' => 3.8, 'is_featured' => true, 'is_best_seller' => true,
            'stock' => 40, 'tags' => ['camping', 'waterproof', 'unisex'],
        ],
        [
            'name' => 'Quechua Camping Lantern LED',
            'sku_prefix' => 'QUE-CL',
            'brand' => 'quechua', 'category' => 'camping-lanterns',
            'short_description' => 'Rechargeable LED camping lantern',
            'description' => '<p>Bright and portable LED camping lantern with 3 brightness settings. Rechargeable via USB with up to 12 hours of battery life. Magnetic base for hands-free use. IPX4 water resistant.</p><ul><li>300 lumens max</li><li>3 brightness settings</li><li>USB rechargeable</li><li>12 hours battery life</li><li>IPX4 water resistant</li></ul>',
            'product_type' => 'simple', 'price' => 899, 'compare_price' => 1299,
            'weight' => 0.3, 'is_featured' => false, 'is_trending' => true,
            'stock' => 200, 'tags' => ['camping', 'eco-friendly', 'unisex'],
        ],
    ];

    public function run(): void
    {
        $this->command->info('Seeding products with full relationships...');

        $colorAttr = Attribute::where('slug', 'color')->first();
        $sizeAttr = Attribute::where('slug', 'clothing-size')->first();
        $shoeSizeAttr = Attribute::where('slug', 'shoe-size-uk')->first();

        $allBrands = Brand::pluck('id', 'slug');
        $allCategories = Category::pluck('id', 'slug');
        $allTags = ProductTag::pluck('id', 'slug');
        $colorValues = AttributeValue::where('attribute_id', $colorAttr?->id)->pluck('id', 'slug');
        $sizeValues = AttributeValue::where('attribute_id', $sizeAttr?->id)->pluck('id', 'slug');
        $shoeSizeValues = AttributeValue::where('attribute_id', $shoeSizeAttr?->id)->pluck('id', 'slug');

        foreach ($this->products as $index => $data) {
            $product = Product::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'sku_prefix' => $data['sku_prefix'],
                'brand_id' => $allBrands->get($data['brand']) ?? null,
                'category_id' => $allCategories->get($data['category']) ?? null,
                'short_description' => $data['short_description'],
                'description' => $data['description'],
                'product_type' => $data['product_type'] ?? 'simple',
                'status' => 'active',
                'visibility' => 'visible',
                'is_featured' => $data['is_featured'] ?? false,
                'is_new' => $data['is_new'] ?? false,
                'is_best_seller' => $data['is_best_seller'] ?? false,
                'is_trending' => $data['is_trending'] ?? false,
                'manage_stock' => true,
                'stock_quantity' => $data['stock'] ?? 50,
                'low_stock_threshold' => 5,
                'weight' => $data['weight'] ?? null,
                'published_at' => now()->subDays(rand(1, 30)),
                'created_by' => 1,
            ]);

            // Assign to product_categories pivot
            if ($product->category_id) {
                DB::table('product_categories')->insert([
                    'product_id' => $product->id,
                    'category_id' => $product->category_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Create featured image
            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => $this->imageUrls[$index % count($this->imageUrls)],
                'alt_text' => $data['name'],
                'is_featured' => true,
                'sort_order' => 0,
            ]);

            // Additional images
            for ($img = 1; $img <= 2; $img++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $this->imageUrls[($index + $img) % count($this->imageUrls)],
                    'alt_text' => $data['name'] . ' image ' . ($img + 1),
                    'is_featured' => false,
                    'sort_order' => $img,
                ]);
            }

            // Create variants if variable product
            if (!empty($data['variants'])) {
                foreach ($data['variants'] as $varIndex => $varData) {
                    $colorId = $colorValues->get($varData['color']) ?? null;

                    if (!empty($varData['sizes'])) {
                        foreach ($varData['sizes'] as $sizeIndex => $sizeSlug) {
                            $sizeId = $shoeSizeValues->get($sizeSlug) ?? $sizeValues->get($sizeSlug) ?? null;
                            $price = $data['price'] + ($varData['price_offset'] ?? 0);
                            $comparePrice = $data['compare_price'] + ($varData['price_offset'] ?? 0);

                            $variant = ProductVariant::create([
                                'product_id' => $product->id,
                                'sku' => $data['sku_prefix'] . '-' . strtoupper($varData['color'] . '-' . $sizeSlug),
                                'price' => $price,
                                'compare_price' => $comparePrice,
                                'cost_price' => round($price * 0.5, 2),
                                'stock_quantity' => max(5, intval($data['stock'] / 10)),
                                'manage_stock' => true,
                                'status' => true,
                                'availability_status' => 'in_stock',
                            ]);

                            if ($colorId) {
                                VariantAttributeValue::create([
                                    'variant_id' => $variant->id,
                                    'attribute_id' => $colorAttr->id,
                                    'attribute_value_id' => $colorId,
                                ]);
                            }
                            if ($sizeId) {
                                $sizeAttribute = $shoeSizeValues->has($sizeSlug) ? $shoeSizeAttr : $sizeAttr;
                                if ($sizeAttribute) {
                                    VariantAttributeValue::create([
                                        'variant_id' => $variant->id,
                                        'attribute_id' => $sizeAttribute->id,
                                        'attribute_value_id' => $sizeId,
                                    ]);
                                }
                            }
                        }
                    } else {
                        $price = $data['price'] + ($varData['price_offset'] ?? 0);
                        $variant = ProductVariant::create([
                            'product_id' => $product->id,
                            'sku' => $data['sku_prefix'] . '-' . strtoupper($varData['color']),
                            'price' => $price,
                            'compare_price' => $data['compare_price'] + ($varData['price_offset'] ?? 0),
                            'cost_price' => round($price * 0.5, 2),
                            'stock_quantity' => max(5, intval($data['stock'] / 5)),
                            'manage_stock' => true,
                            'status' => true,
                            'availability_status' => 'in_stock',
                        ]);

                        if ($colorId) {
                            VariantAttributeValue::create([
                                'variant_id' => $variant->id,
                                'attribute_id' => $colorAttr->id,
                                'attribute_value_id' => $colorId,
                            ]);
                        }
                    }
                }
            } else {
                // Simple product: create a single variant
                ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $data['sku_prefix'] . '-STD',
                    'price' => $data['price'],
                    'compare_price' => $data['compare_price'],
                    'cost_price' => round($data['price'] * 0.5, 2),
                    'stock_quantity' => $data['stock'],
                    'manage_stock' => true,
                    'status' => true,
                    'availability_status' => 'in_stock',
                ]);
            }

            // Attach tags
            if (!empty($data['tags'])) {
                $tagIds = [];
                foreach ($data['tags'] as $tagSlug) {
                    $tagId = $allTags->get($tagSlug);
                    if ($tagId) {
                        $tagIds[] = $tagId;
                    }
                }
                if (!empty($tagIds)) {
                    $product->tags()->sync($tagIds);
                }
            }

            // Assign to random collections
            $collectionCount = rand(1, 3);
            $collections = Collection::inRandomOrder()->limit($collectionCount)->pluck('id');
            foreach ($collections as $collId) {
                DB::table('collection_products')->insert([
                    'collection_id' => $collId,
                    'product_id' => $product->id,
                    'sort_order' => $index,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Create related products
        $allProductIds = Product::pluck('id')->toArray();
        $relatedCount = 0;
        foreach (array_slice($allProductIds, 0, 15) as $pid) {
            $relatedIds = collect($allProductIds)->reject(fn($id) => $id === $pid)->random(3)->toArray();
            foreach ($relatedIds as $idx => $rid) {
                DB::table('related_products')->insert([
                    'product_id' => $pid,
                    'related_product_id' => $rid,
                    'type' => ['related', 'upsell', 'cross_sell'][$idx % 3],
                    'sort_order' => $idx,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $relatedCount++;
            }
        }

        $total = Product::count();
        $this->command->info("✅ Products seeded — {$total} products with variants, images, tags, categories, collections, and {$relatedCount} related product links.");
    }
}
