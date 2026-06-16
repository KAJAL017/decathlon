-- DATABASE CLEANUP SCRIPT
-- Preserves structure, removes all data

SET FOREIGN_KEY_CHECKS = 0;

-- Clear all application tables (excluding migrations)
TRUNCATE TABLE `sessions`;
TRUNCATE TABLE `jobs`;
TRUNCATE TABLE `job_batches`;
TRUNCATE TABLE `failed_jobs`;
TRUNCATE TABLE `cache`;
TRUNCATE TABLE `cache_locks`;
TRUNCATE TABLE `password_reset_tokens`;
TRUNCATE TABLE `customer_password_resets`;

TRUNCATE TABLE `stock_movements`;
TRUNCATE TABLE `product_metrics`;
TRUNCATE TABLE `product_performance_summary`;
TRUNCATE TABLE `product_competitor_analysis`;
TRUNCATE TABLE `product_search_terms`;
TRUNCATE TABLE `product_bundles`;
TRUNCATE TABLE `bundle_items`;
TRUNCATE TABLE `bundle_categories`;
TRUNCATE TABLE `product_import_export_jobs`;
TRUNCATE TABLE `product_slug_history`;
TRUNCATE TABLE `product_faqs`;
TRUNCATE TABLE `product_videos`;
TRUNCATE TABLE `product_versions`;
TRUNCATE TABLE `product_downloads`;
TRUNCATE TABLE `product_reviews`;
TRUNCATE TABLE `product_sections`;
TRUNCATE TABLE `product_images`;
TRUNCATE TABLE `product_attribute_values`;
TRUNCATE TABLE `product_categories`;
TRUNCATE TABLE `product_tags`;
TRUNCATE TABLE `product_tag_items`;
TRUNCATE TABLE `related_products`;
TRUNCATE TABLE `variant_attribute_values`;
TRUNCATE TABLE `product_variants`;
TRUNCATE TABLE `products`;

TRUNCATE TABLE `cart_items`;
TRUNCATE TABLE `carts`;

TRUNCATE TABLE `orders`;
TRUNCATE TABLE `order_items`;
TRUNCATE TABLE `invoices`;
TRUNCATE TABLE `returns`;

TRUNCATE TABLE `customer_addresses`;
TRUNCATE TABLE `customers`;

TRUNCATE TABLE `categories`;
TRUNCATE TABLE `attribute_values`;
TRUNCATE TABLE `attributes`;
TRUNCATE TABLE `attribute_groups`;

TRUNCATE TABLE `brands`;
TRUNCATE TABLE `stores`;
TRUNCATE TABLE `collections`;
TRUNCATE TABLE `collection_products`;
TRUNCATE TABLE `coupons`;
TRUNCATE TABLE `promotions`;
TRUNCATE TABLE `email_campaigns`;
TRUNCATE TABLE `warehouses`;
TRUNCATE TABLE `banners`;
TRUNCATE TABLE `home_sections`;
TRUNCATE TABLE `settings`;
TRUNCATE TABLE `media_settings`;
TRUNCATE TABLE `webhooks`;
TRUNCATE TABLE `ai_usage_logs`;
TRUNCATE TABLE `activity_logs`;

TRUNCATE TABLE `role_permission`;
TRUNCATE TABLE `permissions`;
TRUNCATE TABLE `roles`;
TRUNCATE TABLE `users`;

SET FOREIGN_KEY_CHECKS = 1;

-- Reset auto-increment counters
ALTER TABLE `users` AUTO_INCREMENT = 1;
ALTER TABLE `customers` AUTO_INCREMENT = 1;
ALTER TABLE `customer_addresses` AUTO_INCREMENT = 1;
ALTER TABLE `products` AUTO_INCREMENT = 1;
ALTER TABLE `product_variants` AUTO_INCREMENT = 1;
ALTER TABLE `product_images` AUTO_INCREMENT = 1;
ALTER TABLE `product_categories` AUTO_INCREMENT = 1;
ALTER TABLE `product_tags` AUTO_INCREMENT = 1;
ALTER TABLE `product_attribute_values` AUTO_INCREMENT = 1;
ALTER TABLE `product_reviews` AUTO_INCREMENT = 1;
ALTER TABLE `product_sections` AUTO_INCREMENT = 1;
ALTER TABLE `product_faqs` AUTO_INCREMENT = 1;
ALTER TABLE `product_videos` AUTO_INCREMENT = 1;
ALTER TABLE `product_versions` AUTO_INCREMENT = 1;
ALTER TABLE `product_downloads` AUTO_INCREMENT = 1;
ALTER TABLE `categories` AUTO_INCREMENT = 1;
ALTER TABLE `attributes` AUTO_INCREMENT = 1;
ALTER TABLE `attribute_groups` AUTO_INCREMENT = 1;
ALTER TABLE `attribute_values` AUTO_INCREMENT = 1;
ALTER TABLE `brands` AUTO_INCREMENT = 1;
ALTER TABLE `stores` AUTO_INCREMENT = 1;
ALTER TABLE `collections` AUTO_INCREMENT = 1;
ALTER TABLE `orders` AUTO_INCREMENT = 1;
ALTER TABLE `order_items` AUTO_INCREMENT = 1;
ALTER TABLE `invoices` AUTO_INCREMENT = 1;
ALTER TABLE `returns` AUTO_INCREMENT = 1;
ALTER TABLE `carts` AUTO_INCREMENT = 1;
ALTER TABLE `cart_items` AUTO_INCREMENT = 1;
ALTER TABLE `coupons` AUTO_INCREMENT = 1;
ALTER TABLE `promotions` AUTO_INCREMENT = 1;
ALTER TABLE `email_campaigns` AUTO_INCREMENT = 1;
ALTER TABLE `warehouses` AUTO_INCREMENT = 1;
ALTER TABLE `stock_movements` AUTO_INCREMENT = 1;
ALTER TABLE `banners` AUTO_INCREMENT = 1;
ALTER TABLE `home_sections` AUTO_INCREMENT = 1;
ALTER TABLE `settings` AUTO_INCREMENT = 1;
ALTER TABLE `media_settings` AUTO_INCREMENT = 1;
ALTER TABLE `webhooks` AUTO_INCREMENT = 1;
ALTER TABLE `ai_usage_logs` AUTO_INCREMENT = 1;
ALTER TABLE `activity_logs` AUTO_INCREMENT = 1;
ALTER TABLE `roles` AUTO_INCREMENT = 1;
ALTER TABLE `permissions` AUTO_INCREMENT = 1;
ALTER TABLE `role_permission` AUTO_INCREMENT = 1;
ALTER TABLE `related_products` AUTO_INCREMENT = 1;
ALTER TABLE `variant_attribute_values` AUTO_INCREMENT = 1;
ALTER TABLE `product_bundles` AUTO_INCREMENT = 1;
ALTER TABLE `product_import_export_jobs` AUTO_INCREMENT = 1;
ALTER TABLE `product_slug_history` AUTO_INCREMENT = 1;
ALTER TABLE `product_metrics` AUTO_INCREMENT = 1;
ALTER TABLE `product_performance_summary` AUTO_INCREMENT = 1;
ALTER TABLE `product_competitor_analysis` AUTO_INCREMENT = 1;
ALTER TABLE `product_search_terms` AUTO_INCREMENT = 1;
ALTER TABLE `bundle_items` AUTO_INCREMENT = 1;
ALTER TABLE `bundle_categories` AUTO_INCREMENT = 1;
ALTER TABLE `collection_products` AUTO_INCREMENT = 1;
ALTER TABLE `product_tag_items` AUTO_INCREMENT = 1;
