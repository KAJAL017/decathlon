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

    /**
     * Get the customer's full name.
     */
    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Relationship: Customer has many addresses.
     */
    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    /**
     * Relationship: Customer has many orders.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relationship: Customer has many reviews.
     */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }
}
