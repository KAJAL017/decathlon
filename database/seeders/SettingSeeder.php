<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['group' => 'general', 'key' => 'store_name', 'value' => 'Decathlon India', 'type' => 'text', 'label' => 'Store Name', 'description' => 'The name of your store'],
            ['group' => 'general', 'key' => 'store_email', 'value' => 'support@decathlon.com', 'type' => 'text', 'label' => 'Store Email', 'description' => 'Primary contact email'],
            ['group' => 'general', 'key' => 'store_phone', 'value' => '+91 1800-102-3325', 'type' => 'text', 'label' => 'Store Phone', 'description' => 'Customer support phone number'],
            ['group' => 'general', 'key' => 'store_address', 'value' => 'Decathlon Sports India Pvt Ltd, Plot No. 5B, Sector 6, IMT Manesar, Gurugram, Haryana 122050', 'type' => 'textarea', 'label' => 'Store Address', 'description' => 'Business address'],
            ['group' => 'general', 'key' => 'store_currency', 'value' => 'INR', 'type' => 'text', 'label' => 'Currency', 'description' => 'Default currency code'],
            ['group' => 'general', 'key' => 'store_currency_symbol', 'value' => '₹', 'type' => 'text', 'label' => 'Currency Symbol', 'description' => 'Currency display symbol'],

            // Store
            ['group' => 'store', 'key' => 'store_logo', 'value' => '/images/logo.png', 'type' => 'image', 'label' => 'Store Logo', 'description' => 'Logo displayed in header'],
            ['group' => 'store', 'key' => 'store_favicon', 'value' => '/images/favicon.ico', 'type' => 'image', 'label' => 'Favicon', 'description' => 'Browser tab icon'],
            ['group' => 'store', 'key' => 'store_tagline', 'value' => 'Sport for All', 'type' => 'text', 'label' => 'Tagline', 'description' => 'Store tagline shown in header'],
            ['group' => 'store', 'key' => 'store_description', 'value' => 'Decathlon is a one-stop shop for all sports equipment, clothing, and accessories.', 'type' => 'textarea', 'label' => 'Store Description', 'description' => 'About the store'],
            ['group' => 'store', 'key' => 'store_maintenance_mode', 'value' => 'false', 'type' => 'boolean', 'label' => 'Maintenance Mode', 'description' => 'Enable maintenance mode'],

            // Payment
            ['group' => 'payment', 'key' => 'payment_razorpay_enabled', 'value' => 'true', 'type' => 'boolean', 'label' => 'Razorpay Enabled', 'description' => 'Enable Razorpay payment gateway'],
            ['group' => 'payment', 'key' => 'payment_razorpay_key', 'value' => '', 'type' => 'text', 'label' => 'Razorpay Key', 'description' => 'Razorpay API key'],
            ['group' => 'payment', 'key' => 'payment_razorpay_secret', 'value' => '', 'type' => 'text', 'label' => 'Razorpay Secret', 'description' => 'Razorpay API secret'],
            ['group' => 'payment', 'key' => 'payment_cod_enabled', 'value' => 'true', 'type' => 'boolean', 'label' => 'COD Enabled', 'description' => 'Enable Cash on Delivery'],
            ['group' => 'payment', 'key' => 'payment_upi_enabled', 'value' => 'true', 'type' => 'boolean', 'label' => 'UPI Enabled', 'description' => 'Enable UPI payments'],

            // Shipping
            ['group' => 'shipping', 'key' => 'shipping_free_threshold', 'value' => '999', 'type' => 'text', 'label' => 'Free Shipping Threshold', 'description' => 'Minimum order amount for free shipping'],
            ['group' => 'shipping', 'key' => 'shipping_standard_rate', 'value' => '49', 'type' => 'text', 'label' => 'Standard Shipping Rate', 'description' => 'Flat rate for standard shipping'],
            ['group' => 'shipping', 'key' => 'shipping_express_rate', 'value' => '99', 'type' => 'text', 'label' => 'Express Shipping Rate', 'description' => 'Flat rate for express shipping'],
            ['group' => 'shipping', 'key' => 'shipping_standard_days', 'value' => '5-7', 'type' => 'text', 'label' => 'Standard Delivery Days', 'description' => 'Estimated days for standard delivery'],
            ['group' => 'shipping', 'key' => 'shipping_express_days', 'value' => '2-3', 'type' => 'text', 'label' => 'Express Delivery Days', 'description' => 'Estimated days for express delivery'],

            // Tax
            ['group' => 'tax', 'key' => 'tax_enabled', 'value' => 'true', 'type' => 'boolean', 'label' => 'Tax Enabled', 'description' => 'Enable tax calculation'],
            ['group' => 'tax', 'key' => 'tax_rate', 'value' => '18', 'type' => 'text', 'label' => 'GST Rate (%)', 'description' => 'Default GST rate percentage'],
            ['group' => 'tax', 'key' => 'tax_inclusive', 'value' => 'true', 'type' => 'boolean', 'label' => 'Prices Include Tax', 'description' => 'Whether product prices include tax'],

            // SEO
            ['group' => 'seo', 'key' => 'seo_title', 'value' => 'Decathlon India - Sports Equipment, Clothing & Accessories', 'type' => 'text', 'label' => 'Default SEO Title', 'description' => 'Default meta title'],
            ['group' => 'seo', 'key' => 'seo_description', 'value' => 'Shop sports equipment, clothing, footwear and accessories at Decathlon India. Free shipping on orders above ₹999.', 'type' => 'textarea', 'label' => 'Default SEO Description', 'description' => 'Default meta description'],
            ['group' => 'seo', 'key' => 'seo_keywords', 'value' => 'sports, equipment, clothing, shoes, decathlon, fitness, running, football, cricket', 'type' => 'text', 'label' => 'Default SEO Keywords', 'description' => 'Default meta keywords'],

            // Notifications
            ['group' => 'notifications', 'key' => 'email_order_confirmation', 'value' => 'true', 'type' => 'boolean', 'label' => 'Order Confirmation Email', 'description' => 'Send email on order placement'],
            ['group' => 'notifications', 'key' => 'email_shipping_update', 'value' => 'true', 'type' => 'boolean', 'label' => 'Shipping Update Email', 'description' => 'Send email on shipping status change'],
            ['group' => 'notifications', 'key' => 'email_low_stock_alert', 'value' => 'true', 'type' => 'boolean', 'label' => 'Low Stock Alert', 'description' => 'Send admin alert when stock is low'],

            // Social
            ['group' => 'general', 'key' => 'social_facebook', 'value' => 'https://facebook.com/decathlonindia', 'type' => 'text', 'label' => 'Facebook URL', 'description' => 'Facebook page URL'],
            ['group' => 'general', 'key' => 'social_instagram', 'value' => 'https://instagram.com/decathlonindia', 'type' => 'text', 'label' => 'Instagram URL', 'description' => 'Instagram profile URL'],
            ['group' => 'general', 'key' => 'social_twitter', 'value' => 'https://twitter.com/decathlonindia', 'type' => 'text', 'label' => 'Twitter URL', 'description' => 'Twitter profile URL'],
            ['group' => 'general', 'key' => 'social_youtube', 'value' => 'https://youtube.com/decathlonindia', 'type' => 'text', 'label' => 'YouTube URL', 'description' => 'YouTube channel URL'],

            // Advanced
            ['group' => 'advanced', 'key' => 'analytics_google_id', 'value' => '', 'type' => 'text', 'label' => 'Google Analytics ID', 'description' => 'Google Analytics tracking ID'],
            ['group' => 'advanced', 'key' => 'products_per_page', 'value' => '24', 'type' => 'text', 'label' => 'Products Per Page', 'description' => 'Number of products per page'],
            ['group' => 'advanced', 'key' => 'enable_reviews', 'value' => 'true', 'type' => 'boolean', 'label' => 'Enable Reviews', 'description' => 'Allow customers to leave reviews'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                array_merge($setting, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        $this->command->info('✅ Settings seeded — ' . count($settings) . ' settings across groups: general, store, payment, shipping, tax, seo, notifications, advanced.');
    }
}
