<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Category;

class ProductRepository
{
    /**
     * Search products by name, sku, or search_text
     *
     * @param string $query
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search($query, $limit = 6)
    {
        return Product::active()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('search_text', 'like', "%{$query}%");
            })
            ->with(['brand', 'variants' => function ($vq) {
                $vq->orderBy('price')->limit(1);
            }, 'images' => function ($iq) {
                $iq->where('is_featured', true)->limit(1);
            }])
            ->limit($limit)
            ->get();
    }

    /**
     * Search categories by name
     *
     * @param string $query
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchCategories($query, $limit = 5)
    {
        return Category::active()
            ->where('name', 'like', "%{$query}%")
            ->where('show_in_menu', true)
            ->limit($limit)
            ->get(['id', 'name', 'slug']);
    }

    /**
     * Filter products based on request parameters
     *
     * @param array $params
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function filterProducts(array $params, $perPage = 16)
    {
        $query = Product::active();

        // Filter by Category
        if (isset($params['category']) && $params['category'] !== '') {
            $categorySlug = $params['category'];
            $query->where(function($q) use ($categorySlug) {
                $q->whereHas('categories', function($sq) use ($categorySlug) {
                    $sq->where('slug', $categorySlug);
                })->orWhereHas('category', function($sq) use ($categorySlug) {
                    $sq->where('slug', $categorySlug);
                });
            });
        }

        // Filter by Brand
        if (isset($params['brand']) && $params['brand'] !== '') {
            $brandSlug = $params['brand'];
            $query->whereHas('brand', function($q) use ($brandSlug) {
                $q->where('slug', $brandSlug);
            });
        }

        // Filter by Price
        if (isset($params['min_price']) && $params['min_price'] !== '') {
            $query->whereHas('variants', function($q) use ($params) {
                $q->where('price', '>=', $params['min_price']);
            });
        }
        if (isset($params['max_price']) && $params['max_price'] !== '') {
            $query->whereHas('variants', function($q) use ($params) {
                $q->where('price', '<=', $params['max_price']);
            });
        }
        if (isset($params['price']) && $params['price'] !== '') { // Special case for price points
            $query->whereHas('variants', function($q) use ($params) {
                $q->where('price', '<=', $params['price']);
            });
        }

        // Filter by generic attributes (e.g., volume, material, etc.)
        $excludeParams = ['category', 'brand', 'min_price', 'max_price', 'price', 'search', 'sort', 'page'];
        foreach ($params as $key => $value) {
            if (!in_array($key, $excludeParams) && isset($value) && $value !== '') {
                $query->whereHas('attributeValues', function($q) use ($key, $value) {
                    $q->whereHas('attribute', function($sq) use ($key) {
                        $sq->where('code', $key)->orWhere('slug', $key);
                    })->where(function($sq) use ($value) {
                        $sq->where('value', $value)
                           ->orWhereHas('attributeValue', function($ssq) use ($value) {
                               $ssq->where('value', $value)->orWhere('slug', $value);
                           });
                    });
                });
            }
        }

        // Search
        if (isset($params['search']) && $params['search'] !== '') {
            $search = $params['search'];
            $query->where(function($sq) use ($search) {
                $sq->where('name', 'like', "%{$search}%")
                   ->orWhere('search_text', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = $params['sort'] ?? 'latest';
        switch ($sort) {
            case 'price_low':
                $query->withMin('variants', 'price')->orderBy('variants_min_price', 'asc');
                break;
            case 'price_high':
                $query->withMax('variants', 'price')->orderBy('variants_max_price', 'desc');
                break;
            case 'popular':
                $query->orderBy('reviews_count', 'desc');
                break;
            case 'rating':
                $query->orderBy('average_rating', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query->with(['brand', 'featuredImage', 'variants'])
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Find an active product by slug with all necessary relations
     *
     * @param string $slug
     * @return \App\Models\Product|null
     */
    public function findBySlug($slug)
    {
        return Product::active()
            ->where('slug', $slug)
            ->with(['brand', 'images', 'variants.variantAttributes.attribute', 'variants.variantAttributes.attributeValue', 'category.parent', 'tags', 'faqs', 'videos'])
            ->first();
    }
}
