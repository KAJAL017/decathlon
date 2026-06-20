<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'ticket_number',
        'subject',
        'message',
        'category',
        'priority',
        'status',
        'order_id',
        'last_reply_at',
    ];

    protected $casts = [
        'last_reply_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function replies()
    {
        return $this->hasMany(SupportReply::class, 'ticket_id');
    }

    public static function generateTicketNumber(): string
    {
        $prefix = 'TKT-' . date('Y') . '-';
        $last = self::where('ticket_number', 'like', $prefix . '%')->latest()->value('ticket_number');

        if ($last) {
            $number = intval(substr($last, -5)) + 1;
        } else {
            $number = 1;
        }

        return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
