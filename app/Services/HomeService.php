<?php

namespace App\Services;

use App\Models\HomeSection;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Banner;

class HomeService
{
    public function getHomepageData()
    {
        $sections = HomeSection::active()->orderBy('sort_order')->get();
        
        return $sections->map(function($section) {
            $section->data = $this->getSectionData($section);
            return $section;
        });
    }

    protected function getSectionData($section)
    {
        $settings = $section->settings ?? [];
        $limit = $settings['limit'] ?? 10;

        switch ($section->type) {
            case 'hero_categories':
                $query = Category::active();
                $ids = array_filter($settings['category_ids'] ?? []);
                if (!empty($ids)) {
                    return $query->whereIn('id', $ids)
                        ->orderByRaw("FIELD(id, " . implode(',', $ids) . ")")
                        ->get();
                }
                return $query->top()->orderBy('sort_order')->limit($limit)->get();

            case 'hero_banners':
                $query = Banner::active();
                $ids = array_filter($settings['banner_ids'] ?? []);
                if (!empty($ids)) {
                    return $query->whereIn('id', $ids)
                        ->orderByRaw("FIELD(id, " . implode(',', $ids) . ")")
                        ->get();
                }
                return $query->where('position', $settings['position'] ?? 'hero')->orderBy('sort_order')->get();

            case 'category_grid':
                $query = Category::active();
                $ids = array_filter($settings['category_ids'] ?? []);
                if (!empty($ids)) {
                    return $query->whereIn('id', $ids)
                        ->orderByRaw("FIELD(id, " . implode(',', $ids) . ")")
                        ->get();
                }
                return $query->whereNull('parent_id')->orderBy('sort_order')->limit($limit)->get();

            case 'product_slider':
            case 'product_slider_side':
                $query = Product::active()->with(['brand', 'featuredImage', 'variants']);
                
                $ids = array_filter($settings['product_ids'] ?? []);
                if (!empty($ids)) {
                    return $query->whereIn('id', $ids)
                        ->orderByRaw("FIELD(id, " . implode(',', $ids) . ")")
                        ->get();
                }

                $filter = $settings['filter'] ?? 'featured';
                if ($filter === 'featured') $query->featured();
                elseif ($filter === 'latest') $query->new();
                elseif ($filter === 'trending') $query->trending();
                elseif ($filter === 'best_seller') $query->bestSeller();
                elseif ($filter === 'deals') {
                    $query->whereHas('variants', function($q) {
                        $q->whereNotNull('compare_price')->whereColumn('compare_price', '>', 'price');
                    });
                }
                
                return $query->orderBy('created_at', 'desc')->limit($limit)->get();

            case 'promo_banners':
                $query = Banner::active();
                $ids = array_filter($settings['banner_ids'] ?? []);
                if (!empty($ids)) {
                    return $query->whereIn('id', $ids)
                        ->orderByRaw("FIELD(id, " . implode(',', $ids) . ")")
                        ->get();
                }
                return $query->where('position', $settings['position'] ?? 'promo')->orderBy('sort_order')->get();

            case 'banner_grid':
                $query = Banner::active();
                $ids = array_filter($settings['banner_ids'] ?? []);
                if (!empty($ids)) {
                    return $query->whereIn('id', $ids)
                        ->orderByRaw("FIELD(id, " . implode(',', $ids) . ")")
                        ->get();
                }
                return $query->where('position', $settings['position'] ?? 'banner')->orderBy('sort_order')->limit($limit)->get();

            case 'featured_categories':
                $query = Category::active();
                $ids = array_filter($settings['category_ids'] ?? []);
                if (!empty($ids)) {
                    return $query->whereIn('id', $ids)
                        ->orderByRaw("FIELD(id, " . implode(',', $ids) . ")")
                        ->get();
                }
                return $query->where('is_featured', true)->orderBy('sort_order')->limit($limit)->get();

            case 'brands':
                $query = Brand::active();
                $ids = array_filter($settings['brand_ids'] ?? []);
                if (!empty($ids)) {
                    return $query->whereIn('id', $ids)
                        ->orderByRaw("FIELD(id, " . implode(',', $ids) . ")")
                        ->get();
                }
                return $query->orderBy('sort_order')->get();

            case 'accordion':
                $query = Category::active()->whereNull('parent_id');
                $ids = array_filter($settings['category_ids'] ?? []);
                if (!empty($ids)) {
                    $query->whereIn('id', $ids);
                }
                
                return $query->with(['children' => function($q) {
                        $q->active()->orderBy('sort_order');
                    }])
                    ->orderBy('sort_order')
                    ->get();

            case 'promotions':
                $query = \App\Models\Promotion::running();
                $ids = array_filter($settings['promotion_ids'] ?? []);
                if (!empty($ids)) {
                    return $query->whereIn('id', $ids)
                        ->orderByRaw("FIELD(id, " . implode(',', $ids) . ")")
                        ->get();
                }
                return $query->where('show_on_homepage', true)->orderBy('priority', 'desc')->get();

            case 'best_sellers':
                return Product::active()
                    ->bestSeller()
                    ->with(['brand', 'featuredImage', 'variants'])
                    ->limit($limit)
                    ->get();

            case 'featured_product':
                if (!empty($settings['product_id'])) {
                    return Product::active()
                        ->with(['brand', 'featuredImage', 'variants', 'attributeValues.attribute'])
                        ->find($settings['product_id']);
                }
                return Product::active()->featured()->first();

            case 'product_tabs':
                return [
                    'tabs' => [
                        'featured' => Product::active()->featured()->limit(8)->get(),
                        'best_seller' => Product::active()->bestSeller()->limit(8)->get(),
                        'latest' => Product::active()->new()->limit(8)->get(),
                        'trending' => Product::active()->trending()->limit(8)->get(),
                    ]
                ];

            case 'collection_row':
                $collection = null;
                if (!empty($settings['collection_id'])) {
                    $collection = \App\Models\Collection::find($settings['collection_id']);
                } else {
                    $collection = \App\Models\Collection::first();
                }
                
                return [
                    'collection' => $collection,
                    'products' => $collection ? $collection->products()->active()->limit(4)->get() : collect([])
                ];

            case 'multi_column_products':
                return [
                    'columns' => [
                        ['title' => 'New Arrivals', 'products' => Product::active()->new()->limit(3)->get()],
                        ['title' => 'Best Sellers', 'products' => Product::active()->featured()->limit(3)->get()],
                        ['title' => 'Top Rated', 'products' => Product::active()->orderBy('rating', 'desc')->limit(3)->get()],
                    ]
                ];

            case 'rich_text':
                return [
                    'title' => $settings['title'] ?? '',
                    'content' => $settings['content'] ?? '',
                    'button_text' => $settings['button_text'] ?? '',
                    'button_link' => $settings['button_link'] ?? '',
                    'alignment' => $settings['alignment'] ?? 'center',
                ];

            case 'image_with_text':
                return [
                    'image' => $settings['image_url'] ?? '',
                    'title' => $settings['title'] ?? '',
                    'text' => $settings['content'] ?? '',
                    'button_text' => $settings['button_text'] ?? '',
                    'button_link' => $settings['button_link'] ?? '',
                    'alignment' => $settings['alignment'] ?? 'left', // left or right (image position)
                ];

            case 'video':
                return [
                    'video_url' => $settings['video_url'] ?? '',
                    'title' => $settings['title'] ?? '',
                    'subtitle' => $settings['subtitle'] ?? '',
                    'button_text' => $settings['button_text'] ?? '',
                    'button_link' => $settings['button_link'] ?? '',
                    'autoplay' => $settings['autoplay'] ?? true,
                    'overlay' => $settings['overlay'] ?? 0.4,
                ];

            case 'testimonials':
                return $settings['testimonials'] ?? [];

            case 'gallery':
                return $settings['images'] ?? [];

            case 'newsletter':
                return [
                    'title' => $settings['title'] ?? 'Join the Movement',
                    'subtitle' => $settings['subtitle'] ?? 'Subscribe to get special offers, free giveaways, and once-in-a-lifetime deals.',
                    'placeholder' => $settings['placeholder'] ?? 'Enter your email',
                    'button_text' => $settings['button_text'] ?? 'Subscribe',
                ];

            case 'service_highlights':
                return $settings['items'] ?? [
                    ['title' => 'Free Shipping', 'text' => 'On all orders above ₹999', 'icon' => 'truck'],
                    ['title' => '2 Year Warranty', 'text' => 'On all sports equipment', 'icon' => 'shield'],
                    ['title' => 'Easy Returns', 'text' => '30-day hassle-free returns', 'icon' => 'refresh'],
                    ['title' => 'Secure Payment', 'text' => '100% encrypted checkout', 'icon' => 'lock'],
                ];

            case 'countdown_timer':
                return [
                    'title' => $settings['title'] ?? '',
                    'subtitle' => $settings['subtitle'] ?? '',
                    'end_date' => $settings['end_date'] ?? '',
                    'button_text' => $settings['button_text'] ?? '',
                    'button_link' => $settings['button_link'] ?? '',
                    'background_image' => $settings['background_image'] ?? '',
                ];

            case 'faq_section':
                return $settings['faqs'] ?? [];

            case 'collection_list':
                $ids = array_filter($settings['collection_ids'] ?? []);
                if (!empty($ids)) {
                    return \App\Models\Collection::whereIn('id', $ids)
                        ->orderByRaw("FIELD(id, " . implode(',', $ids) . ")")
                        ->get();
                }
                return \App\Models\Collection::latest()->limit(4)->get();

            case 'instagram_feed':
                return [
                    'username' => $settings['username'] ?? '',
                    'images' => $settings['images'] ?? [],
                    'title' => $settings['title'] ?? 'Follow Us @Instagram',
                ];

            case 'price_points':
                return collect($settings['prices'] ?? [499, 999, 1499, 1999]);

            default:
                return collect([]);
        }
    }
}
