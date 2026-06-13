<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OrderTrackingService;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    protected $orderTrackingService;

    public function __construct(OrderTrackingService $orderTrackingService)
    {
        $this->orderTrackingService = $orderTrackingService;
    }

    /**
     * Display the order tracking page.
     */
    public function index()
    {
        return view('admin.pages.order-tracking.index');
    }

    /**
     * Track an order via AJAX.
     */
    public function track(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:3'
        ]);

        try {
            $result = $this->orderTrackingService->track($request->query('query'));
            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get recent tracked orders.
     */
    public function recent()
    {
        try {
            $orders = $this->orderTrackingService->getRecentTracked();
            return response()->json([
                'success' => true,
                'data' => $orders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
