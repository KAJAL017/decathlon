<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Services\ShiprocketService;

class OrderTrackingService
{
    protected $orderRepository;
    protected $shiprocketService;

    public function __construct(OrderRepository $orderRepository, ShiprocketService $shiprocketService)
    {
        $this->orderRepository = $orderRepository;
        $this->shiprocketService = $shiprocketService;
    }

    /**
     * Get tracking information for an order or tracking number.
     *
     * @param string $query
     * @return array
     */
    public function track(string $query): array
    {
        // Try finding by order number first
        $order = $this->orderRepository->findByOrderNumber($query);

        // If not found, try by tracking number
        if (!$order) {
            $order = $this->orderRepository->findByTrackingNumber($query);
        }

        if (!$order) {
            throw new \Exception('Order or tracking number not found.');
        }

        $trackingNumber = $order->tracking_number;

        if (!$trackingNumber) {
            return [
                'order' => $order,
                'tracking' => null,
                'message' => 'Tracking number not yet assigned to this order.'
            ];
        }

        try {
            $trackingData = $this->shiprocketService->trackShipment($trackingNumber);
            
            return [
                'order' => $order,
                'tracking' => $trackingData,
                'success' => true
            ];
        } catch (\Exception $e) {
            return [
                'order' => $order,
                'tracking' => null,
                'success' => false,
                'message' => 'Could not fetch live tracking data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get recent tracked orders.
     */
    public function getRecentTracked()
    {
        return $this->orderRepository->getRecentForTracking();
    }
}
