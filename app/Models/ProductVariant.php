<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'barcode',
        'price',
        'compare_price',
        'cost_price',
        'stock_quantity',
        'low_stock_threshold',
        'manage_stock',
        'allow_backorder',
        'weight',
        'length',
        'width',
        'height',
        'status',
        'availability_status',
        'available_date',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'status' => 'boolean',
        'manage_stock' => 'boolean',
        'stock_quantity' => 'integer',
        'low_stock_threshold' => 'integer',
        'allow_backorder' => 'boolean',
        'available_date' => 'date',
    ];

    protected $appends = ['variant_name'];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeValues()
    {
        return $this->belongsToMany(
            AttributeValue::class,
            'variant_attribute_values',
            'variant_id',
            'attribute_value_id'
        )->withPivot('attribute_id')->withTimestamps();
    }

    public function variantAttributes()
    {
        return $this->hasMany(VariantAttributeValue::class, 'variant_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'variant_id')->orderBy('sort_order');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeInStock($query)
    {
        return $query->whereHas('inventory', function($q) {
            $q->whereRaw('quantity - reserved_quantity > 0');
        });
    }

    // Accessors
    public function getDiscountPercentageAttribute()
    {
        if (!$this->compare_price || $this->compare_price <= $this->price) {
            return 0;
        }

        return round((($this->compare_price - $this->price) / $this->compare_price) * 100);
    }

    public function getProfitMarginAttribute()
    {
        if (!$this->cost_price || $this->cost_price >= $this->price) {
            return 0;
        }

        return round((($this->price - $this->cost_price) / $this->price) * 100, 2);
    }

    public function getVariantNameAttribute()
    {
        $attributes = $this->variantAttributes
            ->map(function($item) {
                return $item->attributeValue->value ?? '';
            })
            ->filter()
            ->implode(' / ');

        return $attributes ?: 'Default';
    }

    /**
     * Get the size attribute value for this variant
     */
    public function getSizeAttribute()
    {
        if ($this->relationLoaded('variantAttributes')) {
            $attribute = $this->variantAttributes->first(function($item) {
                $slug = strtolower($item->attribute->slug ?? '');
                $name = strtolower($item->attribute->name ?? '');
                return strpos($slug, 'size') !== false || strpos($name, 'size') !== false;
            });
            if ($attribute) {
                return $attribute->attributeValue->value ?? null;
            }
        }

        // Fallback to query if relationship not loaded or not found in collection
        $attribute = $this->variantAttributes()
            ->whereHas('attribute', function($q) {
                $q->where('slug', 'like', '%size%')
                  ->orWhere('name', 'like', '%size%');
            })
            ->with('attributeValue')
            ->first();

        return $attribute?->attributeValue?->value;
    }

    /**
     * Get the color attribute value for this variant
     */
    public function getColorAttribute()
    {
        if ($this->relationLoaded('variantAttributes')) {
            $attribute = $this->variantAttributes->first(function($item) {
                $slug = strtolower($item->attribute->slug ?? '');
                $name = strtolower($item->attribute->name ?? '');
                return strpos($slug, 'color') !== false || strpos($slug, 'colour') !== false || 
                       strpos($name, 'color') !== false || strpos($name, 'colour') !== false;
            });
            if ($attribute) {
                return $attribute->attributeValue->value ?? null;
            }
        }

        // Fallback to query
        $attribute = $this->variantAttributes()
            ->whereHas('attribute', function($q) {
                $q->where('slug', 'like', '%color%')
                  ->orWhere('slug', 'like', '%colour%')
                  ->orWhere('name', 'like', '%color%')
                  ->orWhere('name', 'like', '%colour%');
            })
            ->with('attributeValue')
            ->first();

        return $attribute?->attributeValue?->value;
    }
}
