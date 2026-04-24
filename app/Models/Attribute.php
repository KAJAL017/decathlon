<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Attribute extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'group_id',
        'name',
        'slug',
        'type',
        'display_type',
        'is_variant',
        'is_filterable',
        'is_required',
        'is_searchable',
        'unit',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'is_variant' => 'boolean',
        'is_filterable' => 'boolean',
        'is_required' => 'boolean',
        'is_searchable' => 'boolean',
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relationships
    public function group()
    {
        return $this->belongsTo(AttributeGroup::class, 'group_id');
    }

    public function values()
    {
        return $this->hasMany(AttributeValue::class)->orderBy('sort_order');
    }

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($attribute) {
            if (empty($attribute->slug) || trim($attribute->slug) === '') {
                $attribute->slug = static::generateUniqueSlug($attribute->name);
            }
        });

        static::updating(function ($attribute) {
            if ($attribute->isDirty('name')) {
                if (empty($attribute->slug) || trim($attribute->slug) === '') {
                    $attribute->slug = static::generateUniqueSlug($attribute->name, $attribute->id);
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
        return $query->where('status', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
