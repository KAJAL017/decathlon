<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AttributeValue extends Model
{
    protected $fillable = [
        'attribute_id',
        'value',
        'slug',
        'color_code',
        'image_url',
        'image_id',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relationships
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($value) {
            if (empty($value->slug) || trim($value->slug) === '') {
                $value->slug = static::generateUniqueSlug($value->value, $value->attribute_id);
            }
        });

        static::updating(function ($value) {
            if ($value->isDirty('value')) {
                if (empty($value->slug) || trim($value->slug) === '') {
                    $value->slug = static::generateUniqueSlug($value->value, $value->attribute_id, $value->id);
                }
            }
        });
    }

    // Generate unique slug within attribute
    protected static function generateUniqueSlug($value, $attributeId, $id = null)
    {
        $slug = Str::slug($value);
        $originalSlug = $slug;
        $count = 1;

        while (static::slugExists($slug, $attributeId, $id)) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    // Check if slug exists within attribute
    protected static function slugExists($slug, $attributeId, $id = null)
    {
        $query = static::where('slug', $slug)->where('attribute_id', $attributeId);
        
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
        return $query->orderBy('sort_order')->orderBy('value');
    }
}
