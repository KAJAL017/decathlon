<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'type',
        'provider',
        'last_four',
        'upi_id',
        'wallet_name',
        'cardholder_name',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return match ($this->type) {
            'card' => strtoupper($this->provider) . ' •••• ' . $this->last_four,
            'upi' => $this->upi_id,
            'wallet' => ucfirst($this->wallet_name) . ' Wallet',
            default => 'Unknown',
        };
    }
}
