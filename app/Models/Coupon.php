<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Coupon extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code','name','description','discount_type',
        'discount_value','max_discount_amount',
        'buy_quantity','get_quantity','get_discount_percent',
        'min_order_amount','min_quantity',
        'usage_limit','usage_per_user','used_count',
        'applies_to','applicable_ids',
        'customer_eligibility',
        'starts_at','expires_at',
        'combine_with_other_coupons','combine_with_promotions',
        'is_active','created_by',
    ];

    protected $casts = [
        'applicable_ids'              => 'array',
        'starts_at'                   => 'datetime',
        'expires_at'                  => 'datetime',
        'is_active'                   => 'boolean',
        'combine_with_other_coupons'  => 'boolean',
        'combine_with_promotions'     => 'boolean',
        'discount_value'              => 'decimal:2',
        'max_discount_amount'         => 'decimal:2',
        'min_order_amount'            => 'decimal:2',
        'get_discount_percent'        => 'decimal:2',
    ];

    // Auto uppercase code
    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->code = strtoupper(trim($m->code)));
        static::updating(fn($m) => $m->code = strtoupper(trim($m->code)));
    }

    // Status helpers
    public function getStatusAttribute(): string
    {
        if (!$this->is_active) return 'inactive';
        $now = now();
        if ($this->starts_at  && $now->lt($this->starts_at))  return 'scheduled';
        if ($this->expires_at && $now->gt($this->expires_at)) return 'expired';
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return 'exhausted';
        return 'active';
    }

    public function getDiscountLabelAttribute(): string
    {
        return match($this->discount_type) {
            'percentage'    => $this->discount_value.'% OFF',
            'fixed_amount'  => '₹'.number_format($this->discount_value,0).' OFF',
            'free_shipping' => 'Free Shipping',
            'buy_x_get_y'   => 'Buy '.$this->buy_quantity.' Get '.$this->get_quantity,
            default         => '-',
        };
    }

    public function creator() { return $this->belongsTo(User::class,'created_by'); }

    public function scopeActive($q)
    {
        return $q->where('is_active', true)
            ->where(fn($q) => $q->whereNull('starts_at') ->orWhere('starts_at', '<=', now()))
            ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at','>=', now()));
    }

    // Generate random coupon code
    public static function generateCode(string $prefix = ''): string
    {
        do {
            $code = strtoupper($prefix . Str::random(8));
        } while (static::where('code', $code)->exists());
        return $code;
    }
}
