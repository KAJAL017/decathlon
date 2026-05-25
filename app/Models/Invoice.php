<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_number', 'order_id', 'type', 'status',
        'customer_name', 'customer_email', 'customer_phone', 'customer_gstin',
        'billing_address', 'shipping_address',
        'subtotal', 'discount_amount', 'tax_amount', 'shipping_amount', 'total_amount',
        'paid_amount', 'due_amount', 'currency',
        'invoice_date', 'due_date', 'sent_at', 'paid_at',
        'notes', 'terms', 'items', 'created_by',
    ];

    protected $casts = [
        'items'           => 'array',
        'invoice_date'    => 'date',
        'due_date'        => 'date',
        'sent_at'         => 'datetime',
        'paid_at'         => 'datetime',
        'subtotal'        => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount'      => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'total_amount'    => 'decimal:2',
        'paid_amount'     => 'decimal:2',
        'due_amount'      => 'decimal:2',
    ];

    const STATUSES = [
        'draft'     => 'Draft',
        'sent'      => 'Sent',
        'paid'      => 'Paid',
        'overdue'   => 'Overdue',
        'cancelled' => 'Cancelled',
    ];

    const TYPES = [
        'sale'        => 'Sale Invoice',
        'credit_note' => 'Credit Note',
        'proforma'    => 'Proforma Invoice',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateInvoiceNumber(): string
    {
        $year  = date('Y');
        $count = static::whereYear('created_at', $year)->count() + 1;
        return 'INV-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft'     => 'gray',
            'sent'      => 'blue',
            'paid'      => 'green',
            'overdue'   => 'red',
            'cancelled' => 'gray',
            default     => 'gray',
        };
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'sent' && $this->due_date && $this->due_date->isPast();
    }
}
