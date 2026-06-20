<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use SoftDeletes, \App\Traits\HasMediaThumbnails;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'store_id',
        'image_url',
        'image_id',
        'image_responsive',
        'image_width',
        'image_height',
        'banner_url',
        'banner_id',
        'icon_url',
        'icon_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_active',
        'is_featured',
        'is_top',
        'show_in_menu',
        'sort_order',
        'products_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_top' => 'boolean',
        'show_in_menu' => 'boolean',
        'sort_order' => 'integer',
        'products_count' => 'integer',
        'image_responsive' => 'array',
    ];

    protected $appends = ['thumbnail_url'];

    public function getThumbnailUrlAttribute()
    {
        return $this->getThumbnailUrl($this->image_url);
    }

    // Relationships
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    public function products()
    {
        return $this->hasMany(\App\Models\Product::class);
    }

    // Accessors
    public function getFullPathAttribute()
    {
        $path = [$this->name];
        $parent = $this->parent;
        
        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }
        
        return implode(' > ', $path);
    }

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug) || trim($category->slug) === '') {
                $category->slug = static::generateUniqueSlug($category->name);
            }
        });

        static::updating(function ($category) {
            // If name changed and slug is empty or same as old slug, regenerate
            if ($category->isDirty('name')) {
                if (empty($category->slug) || trim($category->slug) === '') {
                    $category->slug = static::generateUniqueSlug($category->name, $category->id);
                }
            }
        });
    }

    // Generate unique slug
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

    // Check if slug exists
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
        return $query->where('is_active', true);
    }

    public function scopeTop($query)
    {
        return $query->where('is_top', true);
    }

    public function scopeParent($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
