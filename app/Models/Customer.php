<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'google_id',
        'phone',
        'password',
        'avatar',
        'date_of_birth',
        'gender',
        'is_active',
        'email_verified',
        'email_verification_token',
        'email_verified_at',
        'last_login_at',
        'last_login_ip',
        'login_count',
        'accepts_marketing',
        'notes',
        'timezone',
        'language',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'email_verification_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'email_verified' => 'boolean',
            'accepts_marketing' => 'boolean',
            'date_of_birth' => 'date',
        ];
    }

    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getInitialsAttribute(): string
    {
        return strtoupper(substr($this->first_name, 0, 1) . substr($this->last_name ?? '', 0, 1));
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar ?? null;
    }

    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function rewards()
    {
        return $this->hasOne(CustomerReward::class);
    }

    public function notifications_panel()
    {
        return $this->hasMany(CustomerNotification::class);
    }

    public function paymentMethods()
    {
        return $this->hasMany(CustomerPaymentMethod::class);
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function recentlyViewed()
    {
        return $this->hasMany(RecentlyViewedProduct::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function getOrCreateRewards(): CustomerReward
    {
        return $this->rewards()->firstOrCreate(['customer_id' => $this->id]);
    }
}
