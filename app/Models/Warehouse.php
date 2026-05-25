<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'code', 'type', 'contact_name', 'contact_email', 'contact_phone',
        'address_line1', 'address_line2', 'city', 'state', 'country', 'pincode',
        'latitude', 'longitude', 'is_active', 'is_default', 'accepts_returns',
        'processing_days', 'notes', 'created_by',
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'is_default'      => 'boolean',
        'accepts_returns' => 'boolean',
        'latitude'        => 'float',
        'longitude'       => 'float',
    ];

    public function getFullAddressAttribute(): string
    {
        return collect([
            $this->address_line1,
            $this->address_line2,
            $this->city,
            $this->state,
            $this->pincode,
            $this->country,
        ])->filter()->implode(', ');
    }
}
