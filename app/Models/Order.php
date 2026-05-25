<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number', 'status', 'payment_status', 'payment_method', 'payment_reference',
        'customer_id', 'customer_name', 'customer_email', 'customer_phone',
        'shipping_name', 'shipping_address', 'shipping_city', 'shipping_state', 'shipping_pincode', 'shipping_country',
        'billing_name', 'billing_address', 'billing_city', 'billing_state', 'billing_pincode',
        'subtotal', 'discount_amount', 'shipping_amount', 'tax_amount', 'total_amount',
        'coupon_code', 'coupon_discount',
        'shipping_method', 'tracking_number', 'shipping_carrier',
        'shipped_at', 'delivered_at', 'estimated_delivery',
        'customer_note', 'admin_note', 'source', 'created_by',
    ];

    protected $casts = [
        'shipped_at'          => 'datetime',
        'delivered_at'        => 'datetime',
        'estimated_delivery'  => 'datetime',
        'subtotal'            => 'decimal:2',
        'discount_amount'     => 'decimal:2',
        'shipping_amount'     => 'decimal:2',
        'tax_amount'          => 'decimal:2',
        'total_amount'        => 'decimal:2',
        'coupon_discount'     => 'decimal:2',
    ];

    // Status options
    const STATUSES = [
        'pending'    => 'Pending',
        'confirmed'  => 'Confirmed',
        'processing' => 'Processing',
        'shipped'    => 'Shipped',
        'delivered'  => 'Delivered',
        'cancelled'  => 'Cancelled',
        'refunded'   => 'Refunded',
    ];

    const PAYMENT_STATUSES = [
        'pending'        => 'Pending',
        'paid'           => 'Paid',
        'failed'         => 'Failed',
        'refunded'       => 'Refunded',
        'partial_refund' => 'Partial Refund',
    ];

    const PAYMENT_METHODS = [
        'cod'           => 'Cash on Delivery',
        'razorpay'      => 'Razorpay',
        'bank_transfer' => 'Bank Transfer',
        'upi'           => 'UPI',
        'card'          => 'Credit/Debit Card',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function returns()
    {
        return $this->hasMany(Return_::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Generate next order number
    public static function generateOrderNumber(): string
    {
        $year  = date('Y');
        $last  = static::whereYear('created_at', $year)->max('id') ?? 0;
        $count = static::whereYear('created_at', $year)->count() + 1;
        return 'ORD-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'yellow',
            'confirmed'  => 'blue',
            'processing' => 'indigo',
            'shipped'    => 'purple',
            'delivered'  => 'green',
            'cancelled'  => 'red',
            'refunded'   => 'gray',
            default      => 'gray',
        };
    }

    public function getPaymentStatusColorAttribute(): string
    {
        return match($this->payment_status) {
            'paid'           => 'green',
            'pending'        => 'yellow',
            'failed'         => 'red',
            'refunded'       => 'gray',
            'partial_refund' => 'orange',
            default          => 'gray',
        };
    }
}
