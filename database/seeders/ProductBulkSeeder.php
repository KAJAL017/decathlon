<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProductBulkSeeder extends Seeder
{
    public function run(): void
    {
        $imagesFile = base_path('verified_images.json');
        
        if (!File::exists($imagesFile)) {
            $this->command->error('verified_images.json not found! Run fetch_images.php first.');
            return;
        }

        $images = json_decode(File::get($imagesFile), true);
        
        if (empty($images)) {
            $this->command->error('verified_images.json is empty!');
            return;
        }

        $this->command->info('Starting bulk product generation (1000 products)...');

        $bar = $this->command->getOutput()->createProgressBar(1000);
        $bar->start();

        for ($i = 0; $i < 1000; $i++) {
            // Create Product
            $product = \Database\Factories\ProductFactory::new()->create();

            // Create at least one variant
            $variant = ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $product->sku_prefix . '-' . strtoupper(\Illuminate\Support\Str::random(6)),
                'price' => rand(100, 5000),
                'compare_price' => rand(5001, 10000),
                'cost_price' => rand(50, 4000),
                'status' => true,
            ]);

            // Add Image
            $imageUrl = $images[$i % count($images)];
            ProductImage::create([
                'product_id' => $product->id,
                'variant_id' => $variant->id,
                'image_url' => $imageUrl,
                'is_featured' => true,
                'sort_order' => 0,
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->command->info("\n1000 products generated successfully!");
    }
}
