<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Collection extends Model
{
    use SoftDeletes, \App\Traits\HasMediaThumbnails;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'store_id',
        'image_url',
        'type',
        'rules',
        'visibility',
        'is_featured',
        'sort_order',
        'seo_title',
        'seo_description',
        'products_count',
        'status',
        'created_by',
    ];

    protected $casts = [
        'rules' => 'array',
        'is_featured' => 'boolean',
        'status' => 'boolean',
        'sort_order' => 'integer',
        'products_count' => 'integer',
    ];

    protected $appends = ['thumbnail_url'];

    public function getThumbnailUrlAttribute()
    {
        return $this->getThumbnailUrl($this->image_url);
    }

    // Accessors
    public function getImageUrlAttribute($value)
    {
        if (!$value) return null;

        // Reject external URLs
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return null;
        }

        // Strip /storage/ prefix if present
        if (str_starts_with($value, 'storage/')) {
            $value = substr($value, 8);
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($value);
    }
    public function setImageUrlAttribute($value)
    {
        if ($value && str_starts_with($value, 'http')) {
            $path = parse_url($value, PHP_URL_PATH);
            if (str_starts_with($path, '/storage/')) {
                $this->attributes['image_url'] = substr($path, 9); // Remove '/storage/'
            } else {
                $this->attributes['image_url'] = $path;
            }
        } else {
            $this->attributes['image_url'] = $value;
        }
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'collection_products')
                    ->withPivot('sort_order')
                    ->withTimestamps()
                    ->orderByPivot('sort_order');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($collection) {
            if (empty($collection->slug)) {
                $collection->slug = static::generateUniqueSlug($collection->name);
            }
        });

        static::updating(function ($collection) {
            if ($collection->isDirty('name') && empty($collection->slug)) {
                $collection->slug = static::generateUniqueSlug($collection->name, $collection->id);
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
        return $query->where('status', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeVisible($query)
    {
        return $query->where('visibility', 'visible');
    }

    public function scopeManual($query)
    {
        return $query->where('type', 'manual');
    }

    public function scopeAuto($query)
    {
        return $query->where('type', 'auto');
    }

    // Helper Methods
    public function updateProductsCount()
    {
        $this->products_count = $this->products()->count();
        $this->save();
    }

    public function syncProducts(array $productIds)
    {
        $syncData = [];
        foreach ($productIds as $index => $productId) {
            $syncData[$productId] = ['sort_order' => $index];
        }
        
        $this->products()->sync($syncData);
        $this->updateProductsCount();
    }

    // Auto-collection: Apply rules to get products
    public function applyAutoRules()
    {
        if ($this->type !== 'auto' || !$this->rules) {
            return;
        }

        $query = Product::query()->active();

        foreach ($this->rules as $rule) {
            $field = $rule['field'] ?? null;
            $operator = $rule['operator'] ?? 'equals';
            $value = $rule['value'] ?? null;

            if (!$field || !$value) continue;

            switch ($operator) {
                case 'equals':
                    $query->where($field, $value);
                    break;
                case 'not_equals':
                    $query->where($field, '!=', $value);
                    break;
                case 'contains':
                    $query->where($field, 'like', "%{$value}%");
                    break;
                case 'greater_than':
                    $query->where($field, '>', $value);
                    break;
                case 'less_than':
                    $query->where($field, '<', $value);
                    break;
            }
        }

        $productIds = $query->pluck('id')->toArray();
        $this->syncProducts($productIds);
    }
}
