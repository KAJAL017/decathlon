<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ProductBundle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'store_id',
        'name',
        'slug',
        'description',
        'image_url',
        'pricing_type',
        'bundle_price',
        'discount_percentage',
        'minimum_price',
        'maximum_price',
        'availability_logic',
        'is_active',
        'is_featured',
        'sort_order',
        'settings',
        'created_by',
    ];

    protected $casts = [
        'bundle_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'minimum_price' => 'decimal:2',
        'maximum_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'settings' => 'array',
    ];

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($bundle) {
            if (empty($bundle->slug)) {
                $bundle->slug = static::generateUniqueSlug($bundle->name);
            }
        });

        static::updating(function ($bundle) {
            if ($bundle->isDirty('name') && empty($bundle->slug)) {
                $bundle->slug = static::generateUniqueSlug($bundle->name, $bundle->id);
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

    // Relationships
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(BundleItem::class, 'bundle_id')->orderBy('sort_order');
    }

    public function requiredItems()
    {
        return $this->hasMany(BundleItem::class, 'bundle_id')->where('is_required', true)->orderBy('sort_order');
    }

    public function optionalItems()
    {
        return $this->hasMany(BundleItem::class, 'bundle_id')->where('is_required', false)->orderBy('sort_order');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'bundle_categories');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByStore($query, $storeId)
    {
        return $query->where('store_id', $storeId);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Accessors
    public function getCalculatedPriceAttribute()
    {
        return $this->calculateBundlePrice();
    }

    public function getOriginalPriceAttribute()
    {
        return $this->items->sum(function ($item) {
            $price = $item->item_price ?? $item->product->price ?? 0;
            return $price * $item->quantity;
        });
    }

    public function getSavingsAmountAttribute()
    {
        return max(0, $this->original_price - $this->calculated_price);
    }

    public function getSavingsPercentageAttribute()
    {
        if ($this->original_price == 0) {
            return 0;
        }
        
        return round(($this->savings_amount / $this->original_price) * 100, 2);
    }

    public function getIsAvailableAttribute()
    {
        return $this->checkAvailability();
    }

    // Methods
    public function calculateBundlePrice($selectedItems = null)
    {
        $items = $selectedItems ?? $this->items;
        
        $totalPrice = $items->sum(function ($item) {
            $price = $item->item_price ?? $item->product->price ?? 0;
            return $price * $item->quantity;
        });

        return match($this->pricing_type) {
            'fixed' => $this->bundle_price ?? $totalPrice,
            'percentage_discount' => $totalPrice * (1 - ($this->discount_percentage / 100)),
            'sum_of_products' => $totalPrice,
            default => $totalPrice
        };
    }

    public function checkAvailability()
    {
        $items = $this->items()->with('product', 'variant')->get();
        
        return match($this->availability_logic) {
            'all_available' => $items->every(function ($item) {
                return $this->isItemAvailable($item);
            }),
            'any_available' => $items->some(function ($item) {
                return $this->isItemAvailable($item);
            }),
            'custom' => $this->checkCustomAvailability($items),
            default => true
        };
    }

    protected function isItemAvailable($item)
    {
        $product = $item->variant ?? $item->product;
        
        if (!$product) {
            return false;
        }
        
        // Check if product/variant is active
        if ($product->status !== 'active') {
            return false;
        }
        
        // Check availability status
        $availabilityStatus = $product->availability_status ?? 'in_stock';
        
        return in_array($availabilityStatus, ['in_stock', 'pre_order', 'backorder']);
    }

    protected function checkCustomAvailability($items)
    {
        // Custom availability logic can be implemented here
        // For now, default to all_available logic
        return $items->every(function ($item) {
            return $this->isItemAvailable($item);
        });
    }

    public function activate()
    {
        $this->is_active = true;
        $this->save();
    }

    public function deactivate()
    {
        $this->is_active = false;
        $this->save();
    }

    public function toggleFeatured()
    {
        $this->is_featured = !$this->is_featured;
        $this->save();
    }
}