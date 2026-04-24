<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Store extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'domain',
        'subdomain',
        'description',
        'logo_url',
        'favicon_url',
        'email',
        'phone',
        'address',
        'status',
        'settings',
        'owner_id',
        'trial_ends_at',
        'subscription_ends_at',
    ];

    protected $casts = [
        'settings' => 'array',
        'trial_ends_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
    ];

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($store) {
            if (empty($store->slug)) {
                $store->slug = static::generateUniqueSlug($store->name);
            }
        });

        static::updating(function ($store) {
            if ($store->isDirty('name') && empty($store->slug)) {
                $store->slug = static::generateUniqueSlug($store->name, $store->id);
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
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function brands()
    {
        return $this->hasMany(Brand::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }

    public function tags()
    {
        return $this->hasMany(ProductTag::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    public function scopeOnTrial($query)
    {
        return $query->whereNotNull('trial_ends_at')
                     ->where('trial_ends_at', '>', now());
    }

    public function scopeTrialExpired($query)
    {
        return $query->whereNotNull('trial_ends_at')
                     ->where('trial_ends_at', '<=', now());
    }

    public function scopeSubscriptionActive($query)
    {
        return $query->whereNotNull('subscription_ends_at')
                     ->where('subscription_ends_at', '>', now());
    }

    public function scopeSubscriptionExpired($query)
    {
        return $query->whereNotNull('subscription_ends_at')
                     ->where('subscription_ends_at', '<=', now());
    }

    // Accessors
    public function getIsOnTrialAttribute()
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function getIsTrialExpiredAttribute()
    {
        return $this->trial_ends_at && $this->trial_ends_at->isPast();
    }

    public function getIsSubscriptionActiveAttribute()
    {
        return $this->subscription_ends_at && $this->subscription_ends_at->isFuture();
    }

    public function getIsSubscriptionExpiredAttribute()
    {
        return $this->subscription_ends_at && $this->subscription_ends_at->isPast();
    }

    public function getFullUrlAttribute()
    {
        if ($this->domain) {
            return 'https://' . $this->domain;
        }
        
        if ($this->subdomain) {
            return 'https://' . $this->subdomain . '.' . config('app.domain');
        }
        
        return null;
    }

    // Helper Methods
    public function activate()
    {
        $this->status = 'active';
        $this->save();
    }

    public function deactivate()
    {
        $this->status = 'inactive';
        $this->save();
    }

    public function suspend()
    {
        $this->status = 'suspended';
        $this->save();
    }

    public function extendTrial($days = 30)
    {
        $this->trial_ends_at = now()->addDays($days);
        $this->save();
    }

    public function extendSubscription($days = 30)
    {
        $this->subscription_ends_at = now()->addDays($days);
        $this->save();
    }
}
