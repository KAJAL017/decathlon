# Shopify-Level Product CRUD Documentation

This document outlines the advanced, "Shopify-level" product management structure inside the Decathlon E-commerce platform. It maps the frontend form inputs in the modal UI to the backend `Product` model, ensuring full tracking of all variables and relationships.

---

## 1. Core Model Structure (`app/Models/Product.php`)

The `Product` model uses `SoftDeletes` and handles robust e-commerce features including physical/digital tracking, advanced SEO, inventory management, and deep relations.

### 1.1 Direct Database Fields (Fillables)
*   **Basic Info:** `name`, `slug`, `sku_prefix`, `product_type`, `brand_id`, `category_id`
*   **Descriptions:** `short_description`, `description`
*   **Status & Visibility:** `status`, `availability_status`, `available_date`, `published_at`, `unpublished_at`, `visibility`
*   **Digital Goods:** `is_digital`, `download_url`, `download_limit`
*   **Badges/Flags:** `is_featured`, `is_new`, `is_best_seller`, `is_trending`
*   **Metrics:** `average_rating`, `reviews_count`
*   **Inventory:** `manage_stock`, `stock_quantity`, `low_stock_threshold`, `allow_backorder`
*   **Shipping & Dimensions:** `weight`, `length`, `width`, `height`
*   **SEO:** `seo_title`, `seo_description`, `seo_keywords`
*   **System Tracking:** `search_text`, `created_by`

### 1.2 Connected Modules & Relationships
The model integrates with the following sub-modules to achieve a Shopify-level catalog:
*   **Categorization:** Brands, Primary Category, Additional Categories (Pivot), Collections (Pivot), Tags (Pivot).
*   **Media & Assets:** Images (with sorting/featured flags), Videos, Downloads (for digital products).
*   **Variants & Specs:** Product Variants (Size, Color, etc.), Attribute Values (Custom technical specs).
*   **Marketing & Cross-Selling:** Related Products, Upsell Products, Cross-Sell Products (All handled via a polymorphic pivot table `related_products`).
*   **Customer Experience:** FAQs, Sections (Builder).
*   **System Audit:** Slug History (prevents broken links on rename), Versions (version control for product data).

---

## 2. Product Form / Modal UI Structure (`index.blade.php`)

The frontend is separated into 7 distinct tabs to handle the massive scope of the Product entity cleanly. Here is how the UI inputs map to the backend:

### TAB 1: DETAILS
*   **Product Name** (`productName`) -> `name`
*   **Slug** (`productSlug`) -> `slug` (Auto-generated if left blank)
*   **Product Type** (`productType`) -> `product_type` (Enum: `simple`, `variable`, `digital`, `service`)
*   **Brand** (`productBrand`) -> `brand_id` (via Searchable Select)
*   **Short Description** (`productShortDescription`) -> `short_description` (HTML/Text)
*   **Description** (`productDescription`) -> `description` (Rich Text / Summernote)

### TAB 2: PRICING & STATUS
*   **Status** (`productStatus`) -> `status` (Enum: `draft`, `active`, `inactive`)
*   **Availability** (`productAvailability`) -> `availability_status` (Enum: `in_stock`, `out_of_stock`, `pre_order`, `backorder`)
*   **Available Date** (`productAvailableDate`) -> `available_date`
*   **Visibility** (`productVisibility`) -> `visibility` (Enum: `visible`, `hidden`, `catalog_only`, `search_only`)
*   **Regular Price** (`productRegularPrice`) -> `min_price` / Variant price parsing
*   **Sale Price** (`productSalePrice`) -> Logic triggers sale parameters / Variant price parsing
*   **SKU** (`productSku`) -> `sku_prefix` (Used as the base for variant SKUs)

*(Note: Physical dimensions like weight/length and deep inventory logic are typically handled here or under variants depending on the `product_type` selection).*

### TAB 3: MEDIA
*   **Product Images** -> Connects to the `images()` relationship. Uses ImageKit/Local uploads, tracks `sort_order` and `is_featured` flags.

### TAB 4: VARIANTS
*   **Variant Builder** -> Only active if `product_type` == 'variable'. Generates matrices (e.g., Size × Color) mapping to the `ProductVariant` model and `ProductAttributeValue`.

### TAB 5: ORGANIZATION
*   **Primary Category** (`productPrimaryCategory`) -> `category_id` (1-to-1)
*   **Additional Categories** (`productAdditionalCategories`) -> Maps to `categories()` pivot table.
*   **Tags** (`productTags`) -> Maps to `tags()` pivot table.
*   **Collections** (`productCollections`) -> Maps to `collections()` pivot table.
*   **Badges (Checkboxes):** 
    *   `productIsFeatured` -> `is_featured`
    *   `productIsNew` -> `is_new`
    *   `productIsBestSeller` -> `is_best_seller`
    *   `productIsTrending` -> `is_trending`
    *   `productIsDigital` -> `is_digital`

### TAB 6: SEO
*   **SEO Title** (`productSeoTitle`) -> `seo_title`
*   **SEO Description** (`productSeoDescription`) -> `seo_description` (Has live Google preview)
*   **SEO Keywords** (`productSeoKeywords`) -> `seo_keywords`

### TAB 7: ADVANCED
*   **Related Products** -> Cross-sell, Upsell, and standard related products connecting to the `related_products` pivot table.
*   **Product Attributes** -> Custom technical specifications mapped to the `attributeValues()` relationship (e.g., "Material: Cotton").
*   **FAQs & Videos** -> Maps to `ProductFaq` and `ProductVideo` relations.

---

## 3. Notable "Shopify-Level" Capabilities
1. **Auto-Slug & Redirection:** If a product name changes, the slug updates, and the old slug is saved in `ProductSlugHistory` to prevent 404s.
2. **Version Control:** Edits to a product track to `ProductVersion`, allowing rollback capabilities for accidental overrides.
3. **Advanced Variant Matrices:** Generates complex SKUs and pricing overrides natively.
4. **Rich Relational Logic:** Supports Upselling/Cross-selling at the database pivot layer, crucial for modern e-commerce checkout flows.
5. **Search Optimization:** The `search_text` field is auto-compiled on save (combining name, description, SKU) for lightning-fast frontend queries.