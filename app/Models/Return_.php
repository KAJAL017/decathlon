<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Return_ extends Model
{
    use SoftDeletes;

    protected $table = 'returns';

    protected $fillable = [
        'return_number', 'order_id', 'status', 'type', 'reason', 'description',
        'refund_amount', 'refund_method', 'refund_reference', 'refunded_at',
        'admin_note', 'handled_by', 'items',
    ];

    protected $casts = [
        'items'         => 'array',
        'refund_amount' => 'decimal:2',
        'refunded_at'   => 'datetime',
    ];

    const STATUSES = [
        'requested' => 'Requested',
        'approved'  => 'Approved',
        'rejected'  => 'Rejected',
        'received'  => 'Received',
        'refunded'  => 'Refunded',
        'closed'    => 'Closed',
    ];

    const REASONS = [
        'damaged'          => 'Damaged / Defective',
        'wrong_item'       => 'Wrong Item Received',
        'not_as_described' => 'Not as Described',
        'changed_mind'     => 'Changed Mind',
        'size_issue'       => 'Size / Fit Issue',
        'other'            => 'Other',
    ];

    const TYPES = [
        'return'       => 'Return',
        'exchange'     => 'Exchange',
        'refund_only'  => 'Refund Only',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function handledBy()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public static function generateReturnNumber(): string
    {
        $year  = date('Y');
        $count = static::whereYear('created_at', $year)->count() + 1;
        return 'RET-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'requested' => 'yellow',
            'approved'  => 'blue',
            'rejected'  => 'red',
            'received'  => 'indigo',
            'refunded'  => 'green',
            'closed'    => 'gray',
            default     => 'gray',
        };
    }
}
