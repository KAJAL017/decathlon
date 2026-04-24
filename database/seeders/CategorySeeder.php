<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Sports Equipment',
                'slug' => 'sports-equipment',
                'description' => 'All types of sports equipment and gear',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Fitness',
                'slug' => 'fitness',
                'description' => 'Fitness equipment and accessories',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Outdoor',
                'slug' => 'outdoor',
                'description' => 'Outdoor activities and camping gear',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Cycling',
                'slug' => 'cycling',
                'description' => 'Bicycles and cycling accessories',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Water Sports',
                'slug' => 'water-sports',
                'description' => 'Swimming, diving and water sports equipment',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Add some subcategories
        $sportsEquipment = Category::where('slug', 'sports-equipment')->first();
        if ($sportsEquipment) {
            Category::create([
                'name' => 'Football',
                'slug' => 'football',
                'description' => 'Football equipment and accessories',
                'parent_id' => $sportsEquipment->id,
                'is_active' => true,
                'sort_order' => 1,
            ]);

            Category::create([
                'name' => 'Basketball',
                'slug' => 'basketball',
                'description' => 'Basketball equipment and accessories',
                'parent_id' => $sportsEquipment->id,
                'is_active' => true,
                'sort_order' => 2,
            ]);
        }

        $fitness = Category::where('slug', 'fitness')->first();
        if ($fitness) {
            Category::create([
                'name' => 'Gym Equipment',
                'slug' => 'gym-equipment',
                'description' => 'Home and commercial gym equipment',
                'parent_id' => $fitness->id,
                'is_active' => true,
                'sort_order' => 1,
            ]);

            Category::create([
                'name' => 'Yoga',
                'slug' => 'yoga',
                'description' => 'Yoga mats, blocks and accessories',
                'parent_id' => $fitness->id,
                'is_active' => true,
                'sort_order' => 2,
            ]);
        }
    }
}
