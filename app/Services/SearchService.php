<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Support\Str;

class SearchService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Get search suggestions including products and categories
     *
     * @param string $query
     * @return array
     */
    public function getSuggestions($query)
    {
        $query = trim($query);

        if (strlen($query) < 2) {
            return $this->getDefaultState();
        }

        $products = $this->productRepository->search($query);
        $categories = $this->productRepository->searchCategories($query);

        return [
            'products' => $this->formatProducts($products),
            'categories' => $categories
        ];
    }

    /**
     * Get default state for empty search overlay (trending, popular, etc.)
     *
     * @return array
     */
    public function getDefaultState()
    {
        return [
            'is_default' => true,
            'trending' => \App\Models\ProductTag::active()->ordered()->limit(12)->pluck('name'),
            'popular' => $this->formatProducts(
                \App\Models\Product::active()->trending()->with(['brand', 'variants', 'images'])->limit(8)->get()
            )
        ];
    }

    /**
     * Get product details for Quick View modal
     *
     * @param string $slug
     * @return array|null
     */
    public function getProductDetail($slug)
    {
        $product = $this->productRepository->findBySlug($slug);
        
        if (!$product) return null;

        $variants = $product->variants->map(function($v) {
            $attrs = [];
            foreach ($v->variantAttributes as $va) {
                if ($va->attribute) {
                    $attrs[$va->attribute->name] = [
                        'value' => $va->attributeValue?->value,
                        'color' => $va->attributeValue?->color_code
                    ];
                }
            }
            return [
                'id' => $v->id,
                'name' => $v->variant_name,
                'price' => $v->price,
                'compare_price' => $v->compare_price,
                'sku' => $v->sku,
                'stock' => $v->stock_quantity,
                'attributes' => $attrs
            ];
        });

        // Group unique options for separate rows
        $options = [];
        $tempOptions = [];
        foreach ($variants as $v) {
            foreach ($v['attributes'] as $name => $data) {
                $val = $data['value'];
                $col = $data['color'];
                $tempOptions[$name][$val] = $col;
            }
        }

        foreach ($tempOptions as $name => $values) {
            $vals = [];
            foreach ($values as $v => $c) {
                $vals[] = ['label' => $v, 'color' => $c];
            }
            $options[] = [
                'name' => $name,
                'values' => $vals
            ];
        }

        return [
            'id' => $product->id,
            'name' => $product->name,
            'brand' => $product->brand?->name,
            'slug' => $product->slug,
            'description' => $product->short_description ?? Str::limit(strip_tags($product->description), 160),
            'price' => $product->variants->first()?->price,
            'compare_price' => $product->variants->first()?->compare_price,
            'images' => $product->images->map(fn($img) => $img->image_url),
            'variants' => $variants->map(function($v) {
                // Flatten attributes for easier matching in JS
                $flatAttrs = [];
                foreach ($v['attributes'] as $name => $data) {
                    $flatAttrs[$name] = $data['value'];
                }
                $v['attributes'] = $flatAttrs;
                return $v;
            }),
            'options' => $options,
            'has_variants' => $product->variants->count() > 1 || ($product->variants->count() === 1 && $product->variants->first()->variant_name !== 'Default')
        ];
    }

    /**
     * Format product collection for JSON response
     *
     * @param \Illuminate\Database\Eloquent\Collection $products
     * @return \Illuminate\Support\Collection
     */
    protected function formatProducts($products)
    {
        return $products->map(function ($p) {
            $variant = $p->variants->first();
            $image = $p->images->first();

            return [
                'id'           => $p->id,
                'variant_id'   => $variant?->id,
                'has_variants' => $p->variants->count() > 1,
                'name'         => $p->name,
                'brand'        => $p->brand?->name,
                'slug'         => $p->slug,
                'price'        => $variant?->price,
                'image'        => $image?->image_url ?? $image?->path ?? null,
                'rating'       => $p->average_rating,
                'reviews'      => $p->reviews_count,
            ];
        });
    }
}
