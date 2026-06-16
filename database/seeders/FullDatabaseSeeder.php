<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class FullDatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->command->info('==========================================================');
        $this->command->info('  FULL DATABASE SEED — Decathlon Sports E-Commerce');
        $this->command->info('==========================================================');
        $this->command->info('');

        // Step 1: Reset database
        $this->command->info('Step 1: Resetting database...');
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        $tables = [
            'stock_movements', 'ai_usage_logs', 'webhooks', 'email_campaigns',
            'product_slug_history', 'product_tag_items', 'product_tags',
            'variant_attribute_values', 'product_attribute_values',
            'product_categories', 'collection_products', 'related_products',
            'product_images', 'product_variants', 'product_sections',
            'product_downloads', 'product_videos', 'product_faqs',
            'product_analytics', 'products',
            'order_items', 'orders', 'returns', 'invoices',
            'cart_items', 'carts',
            'customer_addresses', 'customers',
            'categories', 'brands', 'collections',
            'attribute_values', 'attributes', 'attribute_groups',
            'promotions', 'coupons', 'warehouses',
            'home_sections', 'banners', 'settings',
            'products_count', 'media_settings',
            'permission_role', 'permissions', 'roles',
            'users',
        ];
        foreach ($tables as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                DB::table($table)->truncate();
            }
        }
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        $this->command->info('  Database cleared.');
        $this->command->info('');

        // Step 2: Roles & Permissions
        $this->command->info('Step 2: Seeding roles and permissions...');
        $this->call(RolePermissionSeeder::class);
        $this->command->info('');

        // Step 3: Admin Users
        $this->command->info('Step 3: Creating admin users...');
        $this->call(CreateAdminUser::class);
        $this->command->info('');

        // Step 4: Categories (3-level hierarchy)
        $this->command->info('Step 4: Seeding categories...');
        $this->call(CategorySeeder::class);
        $this->command->info('');

        // Step 5: Brands
        $this->command->info('Step 5: Seeding brands...');
        $this->call(BrandSeeder::class);
        $this->command->info('');

        // Step 6: Tags
        $this->command->info('Step 6: Seeding tags...');
        $this->call(TagSeeder::class);
        $this->command->info('');

        // Step 7: Attributes & Values
        $this->command->info('Step 7: Seeding attributes...');
        $this->call(AttributeModuleSeeder::class);
        $this->command->info('');

        // Step 8: Collections
        $this->command->info('Step 8: Seeding collections...');
        $this->call(CollectionSeeder::class);
        $this->command->info('');

        // Step 9: Products (with variants, images, tags, categories, collections)
        $this->command->info('Step 9: Seeding products...');
        $this->call(ProductSeeder::class);
        $this->command->info('');

        // Step 10: Customers & Addresses
        $this->command->info('Step 10: Seeding customers...');
        $this->call(CustomerSeeder::class);
        $this->command->info('');

        // Step 11: Warehouses
        $this->command->info('Step 11: Seeding warehouses...');
        $this->call(WarehouseSeeder::class);
        $this->command->info('');

        // Step 12: Coupons
        $this->command->info('Step 12: Seeding coupons...');
        $this->call(CouponSeeder::class);
        $this->command->info('');

        // Step 13: Promotions
        $this->command->info('Step 13: Seeding promotions...');
        $this->call(PromotionSeeder::class);
        $this->command->info('');

        // Step 14: Orders
        $this->command->info('Step 14: Seeding orders...');
        $this->call(OrderSeeder::class);
        $this->command->info('');

        // Step 15: Reviews
        $this->command->info('Step 15: Seeding product reviews...');
        $this->call(ReviewSeeder::class);
        $this->command->info('');

        // Step 16: Banners
        $this->command->info('Step 16: Seeding banners...');
        $this->call(BannerSeeder::class);
        $this->command->info('');

        // Step 17: Homepage sections
        $this->command->info('Step 17: Seeding homepage sections...');
        $this->call(HomepageSeeder::class);
        $this->command->info('');

        // Step 18: Settings
        $this->command->info('Step 18: Seeding settings...');
        $this->call(SettingSeeder::class);
        $this->command->info('');

        // Step 19: Email Campaigns
        $this->command->info('Step 19: Seeding email campaigns...');
        $this->call(EmailCampaignSeeder::class);
        $this->command->info('');

        // Step 20: Stock Movements
        $this->command->info('Step 20: Seeding stock movements...');
        $this->call(StockMovementSeeder::class);
        $this->command->info('');

        // Final summary
        $this->command->info('==========================================================');
        $this->command->info('  SEED COMPLETE');
        $this->command->info('==========================================================');
        $this->command->info('');

        $counts = [
            'Users' => DB::table('users')->count(),
            'Roles' => DB::table('roles')->count(),
            'Permissions' => DB::table('permissions')->count(),
            'Categories' => DB::table('categories')->count(),
            'Brands' => DB::table('brands')->count(),
            'Products' => DB::table('products')->count(),
            'Product Variants' => DB::table('product_variants')->count(),
            'Product Images' => DB::table('product_images')->count(),
            'Product Tags' => DB::table('product_tags')->count(),
            'Collections' => DB::table('collections')->count(),
            'Customers' => DB::table('customers')->count(),
            'Customer Addresses' => DB::table('customer_addresses')->count(),
            'Orders' => DB::table('orders')->count(),
            'Order Items' => DB::table('order_items')->count(),
            'Coupons' => DB::table('coupons')->count(),
            'Promotions' => DB::table('promotions')->count(),
            'Warehouses' => DB::table('warehouses')->count(),
            'Banners' => DB::table('banners')->count(),
            'Home Sections' => DB::table('home_sections')->count(),
            'Settings' => DB::table('settings')->count(),
            'Reviews' => DB::table('product_reviews')->count(),
            'Email Campaigns' => DB::table('email_campaigns')->count(),
            'Stock Movements' => DB::table('stock_movements')->count(),
            'Attribute Groups' => DB::table('attribute_groups')->count(),
            'Attributes' => DB::table('attributes')->count(),
            'Attribute Values' => DB::table('attribute_values')->count(),
        ];

        foreach ($counts as $table => $count) {
            $this->command->info("  {$table}: {$count}");
        }

        $this->command->info('');
        $this->command->info('Admin login: super@gmail.com / 2580');
        $this->command->info('==========================================================');
    }
}
