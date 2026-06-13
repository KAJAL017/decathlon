<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{
    /**
     * Find an order by its order number.
     *
     * @param string $orderNumber
     * @return Order|null
     */
    public function findByOrderNumber(string $orderNumber): ?Order
    {
        return Order::where('order_number', $orderNumber)->first();
    }

    /**
     * Find an order by its tracking number.
     *
     * @param string $trackingNumber
     * @return Order|null
     */
    public function findByTrackingNumber(string $trackingNumber): ?Order
    {
        return Order::where('tracking_number', $trackingNumber)->first();
    }

    /**
     * Get recent orders for tracking dashboard.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentForTracking(int $limit = 10)
    {
        return Order::whereNotNull('tracking_number')
            ->orderByDesc('updated_at')
            ->limit($limit)
            ->get();
    }
}
