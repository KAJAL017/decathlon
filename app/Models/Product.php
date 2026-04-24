<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'sku_prefix',
        'brand_id',
        'category_id',
        'short_description',
        'description',
        'product_type',
        'status',
        'availability_status',
        'available_date',
        'published_at',
        'unpublished_at',
        'visibility',
        'is_digital',
        'download_url',
        'download_limit',
        'is_featured',
        'is_new',
        'is_best_seller',
        'average_rating',
        'reviews_count',
        'weight',
        'length',
        'width',
        'height',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'search_text',
        'created_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'unpublished_at' => 'datetime',
        'available_date' => 'date',
        'is_digital' => 'boolean',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_best_seller' => 'boolean',
        'average_rating' => 'decimal:2',
        'reviews_count' => 'integer',
        'download_limit' => 'integer',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
    ];

    // Relationships
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class)->orderBy('price');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function featuredImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_featured', true)->orderBy('sort_order');
    }

    public function attributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tags()
    {
        return $this->belongsToMany(ProductTag::class, 'product_tag_items', 'product_id', 'tag_id')
                    ->withTimestamps();
    }

    public function relatedProducts()
    {
        return $this->belongsToMany(
            Product::class,
            'related_products',
            'product_id',
            'related_product_id'
        )->withPivot(['type', 'sort_order'])->withTimestamps();
    }

    public function upsellProducts()
    {
        return $this->belongsToMany(
            Product::class,
            'related_products',
            'product_id',
            'related_product_id'
        )->wherePivot('type', 'upsell')
         ->withPivot(['type', 'sort_order'])
         ->withTimestamps()
         ->orderByPivot('sort_order');
    }

    public function crossSellProducts()
    {
        return $this->belongsToMany(
            Product::class,
            'related_products',
            'product_id',
            'related_product_id'
        )->wherePivot('type', 'cross_sell')
         ->withPivot(['type', 'sort_order'])
         ->withTimestamps()
         ->orderByPivot('sort_order');
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'collection_products')
                    ->withPivot('sort_order')
                    ->withTimestamps()
                    ->orderByPivot('sort_order');
    }

    public function slugHistory()
    {
        return $this->hasMany(ProductSlugHistory::class);
    }

    public function videos()
    {
        return $this->hasMany(ProductVideo::class)->ordered();
    }

    public function featuredVideo()
    {
        return $this->hasOne(ProductVideo::class)->where('is_featured', true)->where('status', true);
    }

    public function faqs()
    {
        return $this->hasMany(ProductFaq::class)->ordered();
    }

    public function versions()
    {
        return $this->hasMany(ProductVersion::class)->latest();
    }

    public function latestVersion()
    {
        return $this->hasOne(ProductVersion::class)->latestOfMany();
    }

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug) || trim($product->slug) === '') {
                $product->slug = static::generateUniqueSlug($product->name);
            }
            
            // Update search text
            $product->updateSearchText();
        });

        static::created(function ($product) {
            // Create initial version
            ProductVersion::createVersion($product, 'created', 'Product created');
        });

        static::updating(function ($product) {
            // Track slug changes
            if ($product->isDirty('slug') && $product->getOriginal('slug')) {
                \DB::table('product_slug_history')->insert([
                    'product_id' => $product->id,
                    'old_slug' => $product->getOriginal('slug'),
                    'new_slug' => $product->slug,
                    'changed_at' => now(),
                ]);
            }
            
            if ($product->isDirty('name')) {
                if (empty($product->slug) || trim($product->slug) === '') {
                    $product->slug = static::generateUniqueSlug($product->name, $product->id);
                }
            }
            
            // Update search text if relevant fields changed
            if ($product->isDirty(['name', 'description', 'short_description', 'sku_prefix'])) {
                $product->updateSearchText();
            }
        });

        static::updated(function ($product) {
            // Track version changes
            $changeType = 'other';
            
            if ($product->wasChanged(['price', 'compare_price', 'cost_price'])) {
                $changeType = 'price_changed';
            } elseif ($product->wasChanged(['description', 'short_description'])) {
                $changeType = 'description_updated';
            } elseif ($product->wasChanged(['seo_title', 'seo_description', 'seo_keywords'])) {
                $changeType = 'seo_updated';
            } elseif ($product->wasChanged('status')) {
                $changeType = 'status_changed';
            }

            ProductVersion::trackChanges($product, $changeType);
        });

        static::saved(function ($product) {
            // Update collections count
            foreach ($product->collections as $collection) {
                $collection->updateProductsCount();
            }
        });
    }

    protected static function generateUniqueSlug($name, $id = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (static::slugExists($slug, $id)) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    protected static function slugExists($slug, $id = null)
    {
        $query = static::where('slug', $slug);
        
        if ($id) {
            $query->where('id', '!=', $id);
        }
        
        return $query->exists();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeNew($query)
    {
        return $query->where('is_new', true);
    }

    public function scopeBestSeller($query)
    {
        return $query->where('is_best_seller', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('product_type', $type);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'active')
                     ->where(function($q) {
                         $q->whereNull('published_at')
                           ->orWhere('published_at', '<=', now());
                     })
                     ->where(function($q) {
                         $q->whereNull('unpublished_at')
                           ->orWhere('unpublished_at', '>', now());
                     });
    }

    public function scopeVisible($query)
    {
        return $query->where('visibility', 'visible');
    }

    public function scopeDigital($query)
    {
        return $query->where('is_digital', true);
    }

    public function scopePhysical($query)
    {
        return $query->where('is_digital', false);
    }

    public function scopeByVisibility($query, $visibility)
    {
        return $query->where('visibility', $visibility);
    }

    // Accessors
    public function getPriceRangeAttribute()
    {
        $variants = $this->variants;
        
        if ($variants->isEmpty()) {
            return null;
        }

        $minPrice = $variants->min('price');
        $maxPrice = $variants->max('price');

        if ($minPrice == $maxPrice) {
            return number_format($minPrice, 2);
        }

        return number_format($minPrice, 2) . ' - ' . number_format($maxPrice, 2);
    }

    public function getIsPublishedAttribute()
    {
        if ($this->status !== 'active') {
            return false;
        }

        $now = now();

        if ($this->published_at && $this->published_at > $now) {
            return false;
        }

        if ($this->unpublished_at && $this->unpublished_at <= $now) {
            return false;
        }

        return true;
    }

    public function getPublishingStatusAttribute()
    {
        if ($this->status !== 'active') {
            return 'draft';
        }

        $now = now();

        if ($this->published_at && $this->published_at > $now) {
            return 'scheduled';
        }

        if ($this->unpublished_at && $this->unpublished_at <= $now) {
            return 'expired';
        }

        return 'published';
    }

    // Helper Methods
    public function updateRatingCache()
    {
        // This will be used when reviews module is implemented
        // For now, just a placeholder
        $this->average_rating = 0;
        $this->reviews_count = 0;
        $this->save();
    }

    public function syncTags(array $tagNames)
    {
        $tagIds = [];

        foreach ($tagNames as $tagName) {
            $tag = ProductTag::firstOrCreate(
                ['name' => trim($tagName)],
                ['slug' => Str::slug(trim($tagName))]
            );
            $tagIds[] = $tag->id;
        }

        $this->tags()->sync($tagIds);

        // Update products count for all affected tags
        ProductTag::whereIn('id', $tagIds)->get()->each->updateProductsCount();
    }

    public function updateSearchText()
    {
        $searchParts = [
            $this->name,
            $this->sku_prefix,
            $this->short_description,
            strip_tags($this->description),
        ];

        // Add brand name
        if ($this->brand) {
            $searchParts[] = $this->brand->name;
        }

        // Add category name
        if ($this->category) {
            $searchParts[] = $this->category->name;
        }

        // Add tags
        $tags = $this->tags()->pluck('name')->toArray();
        $searchParts = array_merge($searchParts, $tags);

        // Combine and clean
        $this->search_text = implode(' ', array_filter($searchParts));
    }
}
