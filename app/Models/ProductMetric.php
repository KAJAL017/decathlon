<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProductMetric extends Model
{
    protected $fillable = [
        'store_id',
        'product_id',
        'variant_id',
        'date',
        'views',
        'unique_views',
        'add_to_cart',
        'remove_from_cart',
        'orders',
        'quantity_sold',
        'revenue',
        'profit',
        'returns',
        'return_amount',
        'search_appearances',
        'search_clicks',
        'bounce_rate',
        'time_on_page',
    ];

    protected $casts = [
        'date' => 'date',
        'views' => 'integer',
        'unique_views' => 'integer',
        'add_to_cart' => 'integer',
        'remove_from_cart' => 'integer',
        'orders' => 'integer',
        'quantity_sold' => 'integer',
        'revenue' => 'decimal:2',
        'profit' => 'decimal:2',
        'returns' => 'integer',
        'return_amount' => 'decimal:2',
        'search_appearances' => 'integer',
        'search_clicks' => 'integer',
        'bounce_rate' => 'decimal:2',
        'time_on_page' => 'integer',
    ];

    // Relationships
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    // Scopes
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeForVariant($query, $variantId)
    {
        return $query->where('variant_id', $variantId);
    }

    public function scopeForStore($query, $storeId)
    {
        return $query->where('store_id', $storeId);
    }

    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    public function scopeYesterday($query)
    {
        return $query->whereDate('date', yesterday());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('date', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ]);
    }

    public function scopeLastMonth($query)
    {
        return $query->whereBetween('date', [
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth()
        ]);
    }

    // Accessors
    public function getConversionRateAttribute()
    {
        if ($this->unique_views == 0) {
            return 0;
        }
        
        return round(($this->orders / $this->unique_views) * 100, 2);
    }

    public function getCartConversionRateAttribute()
    {
        if ($this->add_to_cart == 0) {
            return 0;
        }
        
        return round(($this->orders / $this->add_to_cart) * 100, 2);
    }

    public function getAverageOrderValueAttribute()
    {
        if ($this->orders == 0) {
            return 0;
        }
        
        return round($this->revenue / $this->orders, 2);
    }

    public function getProfitMarginAttribute()
    {
        if ($this->revenue == 0) {
            return 0;
        }
        
        return round(($this->profit / $this->revenue) * 100, 2);
    }

    public function getSearchClickRateAttribute()
    {
        if ($this->search_appearances == 0) {
            return 0;
        }
        
        return round(($this->search_clicks / $this->search_appearances) * 100, 2);
    }

    public function getReturnRateAttribute()
    {
        if ($this->orders == 0) {
            return 0;
        }
        
        return round(($this->returns / $this->orders) * 100, 2);
    }

    public function getFormattedTimeOnPageAttribute()
    {
        $seconds = $this->time_on_page;
        
        if ($seconds < 60) {
            return $seconds . 's';
        } elseif ($seconds < 3600) {
            return round($seconds / 60, 1) . 'm';
        } else {
            return round($seconds / 3600, 1) . 'h';
        }
    }

    // Static Methods
    public static function recordView($productId, $variantId = null, $storeId = null, $isUnique = false)
    {
        $metric = static::firstOrCreate([
            'store_id' => $storeId,
            'product_id' => $productId,
            'variant_id' => $variantId,
            'date' => today(),
        ]);

        $metric->increment('views');
        
        if ($isUnique) {
            $metric->increment('unique_views');
        }
    }

    public static function recordAddToCart($productId, $variantId = null, $storeId = null)
    {
        $metric = static::firstOrCreate([
            'store_id' => $storeId,
            'product_id' => $productId,
            'variant_id' => $variantId,
            'date' => today(),
        ]);

        $metric->increment('add_to_cart');
    }

    public static function recordOrder($productId, $variantId = null, $storeId = null, $quantity = 1, $revenue = 0, $profit = 0)
    {
        $metric = static::firstOrCreate([
            'store_id' => $storeId,
            'product_id' => $productId,
            'variant_id' => $variantId,
            'date' => today(),
        ]);

        $metric->increment('orders');
        $metric->increment('quantity_sold', $quantity);
        $metric->increment('revenue', $revenue);
        $metric->increment('profit', $profit);
    }

    public static function recordSearch($productId, $storeId = null, $clicked = false)
    {
        $metric = static::firstOrCreate([
            'store_id' => $storeId,
            'product_id' => $productId,
            'variant_id' => null,
            'date' => today(),
        ]);

        $metric->increment('search_appearances');
        
        if ($clicked) {
            $metric->increment('search_clicks');
        }
    }

    // Aggregation Methods
    public static function getTopPerformers($storeId = null, $period = 'month', $metric = 'revenue', $limit = 10)
    {
        $query = static::with('product');
        
        if ($storeId) {
            $query->where('store_id', $storeId);
        }
        
        // Set date range based on period
        switch ($period) {
            case 'today':
                $query->whereDate('date', today());
                break;
            case 'week':
                $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()]);
                break;
            case 'year':
                $query->whereBetween('date', [now()->startOfYear(), now()->endOfYear()]);
                break;
        }
        
        return $query->selectRaw("
                product_id,
                SUM({$metric}) as total_{$metric},
                SUM(views) as total_views,
                SUM(orders) as total_orders,
                SUM(revenue) as total_revenue
            ")
            ->groupBy('product_id')
            ->orderByDesc("total_{$metric}")
            ->limit($limit)
            ->get();
    }
}