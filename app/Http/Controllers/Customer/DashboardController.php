<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $customerId = $customer->id;

        $recentOrders = $customer->orders()
            ->with('items.product')
            ->latest()
            ->take(5)
            ->get();

        $stats = Cache::remember("customer_{$customerId}_stats", 300, function () use ($customerId) {
            return [
                'active_orders' => \App\Models\Order::where('customer_id', $customerId)
                    ->whereIn('status', ['pending', 'confirmed', 'processing', 'shipped'])
                    ->count(),
                'delivered_orders' => \App\Models\Order::where('customer_id', $customerId)
                    ->where('status', 'delivered')
                    ->count(),
                'total_spent' => \App\Models\Order::where('customer_id', $customerId)
                    ->where('payment_status', 'paid')
                    ->sum('total_amount'),
                'wishlist_count' => \App\Models\Wishlist::where('customer_id', $customerId)->count(),
                'unread_notifications' => \App\Models\CustomerNotification::where('customer_id', $customerId)
                    ->unread()
                    ->count(),
            ];
        });

        $recentlyViewed = $customer->recentlyViewed()
            ->with('product.featuredImage', 'product')
            ->latest('viewed_at')
            ->take(8)
            ->get()
            ->pluck('product');

        $defaultAddress = $customer->addresses()
            ->where('is_default', true)
            ->first();

        $isMobile = request()->attributes->get('is_mobile');

        return view($isMobile ? 'customer.mobile.dashboard' : 'customer.desktop.dashboard', [
            'customer' => $customer,
            'recentOrders' => $recentOrders,
            'activeOrdersCount' => $stats['active_orders'],
            'wishlistCount' => $stats['wishlist_count'],
            'unreadNotifications' => $stats['unread_notifications'],
            'recentlyViewed' => $recentlyViewed,
            'defaultAddress' => $defaultAddress,
        ]);
    }
}
