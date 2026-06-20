<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'points_balance',
        'total_earned',
        'total_redeemed',
        'total_expired',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function transactions()
    {
        return $this->hasMany(RewardTransaction::class, 'reward_id');
    }

    public function earn(int $points, string $description = null, $reference = null): self
    {
        $this->increment('points_balance', $points);
        $this->increment('total_earned', $points);

        $this->transactions()->create([
            'customer_id' => $this->customer_id,
            'type' => 'earned',
            'points' => $points,
            'balance_after' => $this->fresh()->points_balance,
            'description' => $description,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id' => $reference?->id,
            'expires_at' => now()->addYear(),
        ]);

        return $this;
    }

    public function redeem(int $points, string $description = null, $reference = null): self
    {
        if ($this->points_balance < $points) {
            throw new \Exception('Insufficient reward points.');
        }

        $this->decrement('points_balance', $points);
        $this->increment('total_redeemed', $points);

        $this->transactions()->create([
            'customer_id' => $this->customer_id,
            'type' => 'redeemed',
            'points' => $points,
            'balance_after' => $this->fresh()->points_balance,
            'description' => $description,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id' => $reference?->id,
        ]);

        return $this;
    }
}
