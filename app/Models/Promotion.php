<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Promotion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name','slug','description','type',
        'discount_value','max_discount_amount',
        'buy_quantity','get_quantity','get_discount_percent',
        'min_order_amount','min_quantity',
        'usage_limit','usage_per_user','used_count',
        'applies_to','applicable_ids',
        'starts_at','ends_at',
        'badge_text','badge_color','banner_url',
        'show_countdown','show_on_homepage','priority',
        'is_active','created_by',
    ];

    protected $casts = [
        'applicable_ids'     => 'array',
        'starts_at'          => 'datetime',
        'ends_at'            => 'datetime',
        'is_active'          => 'boolean',
        'show_countdown'     => 'boolean',
        'show_on_homepage'   => 'boolean',
        'discount_value'     => 'decimal:2',
        'max_discount_amount'=> 'decimal:2',
        'min_order_amount'   => 'decimal:2',
        'get_discount_percent'=> 'decimal:2',
    ];

    // Auto-slug
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($m) {
            if (empty($m->slug)) $m->slug = static::uniqueSlug($m->name);
        });
    }

    protected static function uniqueSlug($name, $id = null)
    {
        $slug = Str::slug($name); $base = $slug; $i = 1;
        while (static::where('slug', $slug)->when($id, fn($q) => $q->where('id','!=',$id))->exists()) {
            $slug = $base.'-'.$i++;
        }
        return $slug;
    }

    // Helpers
    public function isActive(): bool
    {
        if (!$this->is_active) return false;
        $now = now();
        if ($this->starts_at && $now->lt($this->starts_at)) return false;
        if ($this->ends_at   && $now->gt($this->ends_at))   return false;
        return true;
    }

    public function getStatusLabelAttribute(): string
    {
        if (!$this->is_active) return 'Inactive';
        $now = now();
        if ($this->starts_at && $now->lt($this->starts_at)) return 'Scheduled';
        if ($this->ends_at   && $now->gt($this->ends_at))   return 'Expired';
        return 'Active';
    }

    public function getDiscountLabelAttribute(): string
    {
        return match($this->type) {
            'percentage'    => $this->discount_value.'% OFF',
            'fixed_amount'  => '₹'.number_format($this->discount_value,0).' OFF',
            'free_shipping' => 'Free Shipping',
            'buy_x_get_y'   => 'Buy '.$this->buy_quantity.' Get '.$this->get_quantity,
            'flash_sale'    => $this->discount_value.'% Flash Sale',
            'bundle'        => 'Bundle Deal',
            default         => '-',
        };
    }

    public function creator() { return $this->belongsTo(User::class,'created_by'); }

    public function scopeActive($q) { return $q->where('is_active',true); }
    public function scopeRunning($q) {
        return $q->active()
            ->where(fn($q) => $q->whereNull('starts_at')->orWhere('starts_at','<=',now()))
            ->where(fn($q) => $q->whereNull('ends_at')  ->orWhere('ends_at',  '>=',now()));
    }
}
