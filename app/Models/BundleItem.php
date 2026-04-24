<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BundleItem extends Model
{
    protected $fillable = [
        'bundle_id',
        'product_id',
        'variant_id',
        'quantity',
        'item_price',
        'is_required',
        'is_default_selected',
        'sort_order',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'item_price' => 'decimal:2',
        'is_required' => 'boolean',
        'is_default_selected' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relationships
    public function bundle()
    {
        return $this->belongsTo(ProductBundle::class, 'bundle_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    // Accessors
    public function getEffectivePriceAttribute()
    {
        if ($this->item_price !== null) {
            return $this->item_price;
        }
        
        if ($this->variant) {
            return $this->variant->price ?? 0;
        }
        
        return $this->product->price ?? 0;
    }

    public function getTotalPriceAttribute()
    {
        return $this->effective_price * $this->quantity;
    }

    public function getDisplayNameAttribute()
    {
        $name = $this->product->name ?? 'Unknown Product';
        
        if ($this->variant) {
            $variantName = $this->variant->name ?? 'Variant';
            $name .= ' - ' . $variantName;
        }
        
        if ($this->quantity > 1) {
            $name .= ' (x' . $this->quantity . ')';
        }
        
        return $name;
    }

    public function getIsAvailableAttribute()
    {
        $item = $this->variant ?? $this->product;
        
        if (!$item) {
            return false;
        }
        
        // Check if product/variant is active
        if ($item->status !== 'active') {
            return false;
        }
        
        // Check availability status
        $availabilityStatus = $item->availability_status ?? 'in_stock';
        
        return in_array($availabilityStatus, ['in_stock', 'pre_order', 'backorder']);
    }

    // Scopes
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    public function scopeOptional($query)
    {
        return $query->where('is_required', false);
    }

    public function scopeDefaultSelected($query)
    {
        return $query->where('is_default_selected', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}