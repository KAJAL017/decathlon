<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id','variant_id','type',
        'quantity','quantity_before','quantity_after',
        'reference_type','reference_id',
        'notes','cost_per_unit','location','created_by',
    ];

    protected $casts = [
        'cost_per_unit' => 'decimal:2',
    ];

    public function product()  { return $this->belongsTo(Product::class); }
    public function variant()  { return $this->belongsTo(ProductVariant::class); }
    public function creator()  { return $this->belongsTo(User::class, 'created_by'); }

    public function getDirectionAttribute(): string
    {
        return $this->quantity >= 0 ? 'in' : 'out';
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'purchase'   => 'Purchase',
            'sale'       => 'Sale',
            'return'     => 'Return',
            'adjustment' => 'Adjustment',
            'transfer'   => 'Transfer',
            'damage'     => 'Damage',
            'expired'    => 'Expired',
            default      => ucfirst($this->type),
        };
    }
}
