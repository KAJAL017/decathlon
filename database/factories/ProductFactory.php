<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'sku_prefix' => strtoupper($this->faker->lexify('???')),
            'brand_id' => Brand::inRandomOrder()->first()?->id,
            'category_id' => Category::inRandomOrder()->first()?->id,
            'short_description' => $this->faker->sentence(),
            'description' => $this->faker->paragraphs(3, true),
            'product_type' => $this->faker->randomElement(['simple', 'variable']),
            'status' => 'active',
            'visibility' => 'visible',
            'is_featured' => $this->faker->boolean(20),
            'is_new' => $this->faker->boolean(30),
            'is_best_seller' => $this->faker->boolean(10),
            'manage_stock' => true,
            'stock_quantity' => $this->faker->numberBetween(10, 500),
            'created_by' => User::whereHas('role', function($q) { $q->where('name', 'super_admin'); })->first()?->id ?? 1,
            'weight' => $this->faker->randomFloat(2, 0.1, 10),
            'length' => $this->faker->randomFloat(2, 10, 100),
            'width' => $this->faker->randomFloat(2, 10, 100),
            'height' => $this->faker->randomFloat(2, 10, 100),
        ];
    }
}
