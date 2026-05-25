<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Order;
use App\Models\Return_;
use App\Models\Invoice;
use Illuminate\Support\Facades\Schema;

class NotificationController extends Controller
{
    public function index()
    {
        try {
            $notifications = [];

            // ── 1. Low Stock Products ─────────────────────────────
            if (Schema::hasColumn('products', 'stock_quantity')) {
                $hasManage    = Schema::hasColumn('products', 'manage_stock');
                $hasThreshold = Schema::hasColumn('products', 'low_stock_threshold');

                if ($hasManage && $hasThreshold) {
                    $lowStock = Product::where('manage_stock', true)
                        ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
                        ->where('stock_quantity', '>', 0)
                        ->count();
                    $outStock = Product::where('manage_stock', true)
                        ->where('stock_quantity', '<=', 0)
                        ->count();
                } else {
                    $lowStock = Product::where('stock_quantity', '>', 0)
                        ->where('stock_quantity', '<=', 5)->count();
                    $outStock = Product::where('stock_quantity', '<=', 0)->count();
                }

                if ($outStock > 0) {
                    $notifications[] = [
                        'type'    => 'critical',
                        'icon'    => 'stock',
                        'color'   => 'red',
                        'title'   => $outStock . ' product' . ($outStock > 1 ? 's' : '') . ' out of stock',
                        'sub'     => 'Immediate restocking needed',
                        'link'    => '/admin/stock',
                        'time'    => 'Now',
                        'badge'   => $outStock,
                    ];
                }

                if ($lowStock > 0) {
                    $notifications[] = [
                        'type'    => 'warning',
                        'icon'    => 'stock',
                        'color'   => 'orange',
                        'title'   => $lowStock . ' product' . ($lowStock > 1 ? 's' : '') . ' running low',
                        'sub'     => 'Stock below threshold',
                        'link'    => '/admin/stock',
                        'time'    => 'Now',
                        'badge'   => $lowStock,
                    ];
                }

            }

            // ── 2. Pending Reviews ────────────────────────────────
            $pendingReviews = ProductReview::where('status', 'pending')->count();
            if ($pendingReviews > 0) {
                $notifications[] = [
                    'type'  => 'info',
                    'icon'  => 'review',
                    'color' => 'yellow',
                    'title' => $pendingReviews . ' review' . ($pendingReviews > 1 ? 's' : '') . ' awaiting approval',
                    'sub'   => 'Pending moderation',
                    'link'  => '/admin/reviews',
                    'time'  => 'Now',
                    'badge' => $pendingReviews,
                ];
            }

            // ── 3. Pending Orders ─────────────────────────────────
            if (Schema::hasTable('orders')) {
                $pendingOrders = Order::where('status', 'pending')->count();
                if ($pendingOrders > 0) {
                    $notifications[] = [
                        'type'  => 'info',
                        'icon'  => 'order',
                        'color' => 'blue',
                        'title' => $pendingOrders . ' order' . ($pendingOrders > 1 ? 's' : '') . ' pending',
                        'sub'   => 'Awaiting confirmation',
                        'link'  => '/admin/orders',
                        'time'  => 'Now',
                        'badge' => $pendingOrders,
                    ];
                }

                // New orders today
                $todayOrders = Order::whereDate('created_at', today())->count();
                if ($todayOrders > 0) {
                    $notifications[] = [
                        'type'  => 'success',
                        'icon'  => 'order',
                        'color' => 'green',
                        'title' => $todayOrders . ' new order' . ($todayOrders > 1 ? 's' : '') . ' today',
                        'sub'   => 'Total: ₹' . number_format(Order::whereDate('created_at', today())->sum('total_amount'), 0),
                        'link'  => '/admin/orders',
                        'time'  => 'Today',
                        'badge' => $todayOrders,
                    ];
                }
            }

            // ── 4. Pending Returns ────────────────────────────────
            if (Schema::hasTable('returns')) {
                $pendingReturns = Return_::where('status', 'requested')->count();
                if ($pendingReturns > 0) {
                    $notifications[] = [
                        'type'  => 'warning',
                        'icon'  => 'return',
                        'color' => 'purple',
                        'title' => $pendingReturns . ' return request' . ($pendingReturns > 1 ? 's' : '') . ' pending',
                        'sub'   => 'Awaiting review',
                        'link'  => '/admin/returns',
                        'time'  => 'Now',
                        'badge' => $pendingReturns,
                    ];
                }
            }

            // ── 5. Overdue Invoices ───────────────────────────────
            if (Schema::hasTable('invoices')) {
                $overdueInvoices = Invoice::where('status', 'sent')
                    ->whereNotNull('due_date')
                    ->whereDate('due_date', '<', today())
                    ->count();
                if ($overdueInvoices > 0) {
                    $notifications[] = [
                        'type'  => 'critical',
                        'icon'  => 'invoice',
                        'color' => 'red',
                        'title' => $overdueInvoices . ' invoice' . ($overdueInvoices > 1 ? 's' : '') . ' overdue',
                        'sub'   => 'Payment not received',
                        'link'  => '/admin/invoices',
                        'time'  => 'Now',
                        'badge' => $overdueInvoices,
                    ];
                }
            }

            // ── 6. Expiring Coupons (next 3 days) ────────────────
            $expiringCoupons = \App\Models\Coupon::where('is_active', true)
                ->whereNotNull('expires_at')
                ->whereBetween('expires_at', [now(), now()->addDays(3)])
                ->count();
            if ($expiringCoupons > 0) {
                $notifications[] = [
                    'type'  => 'warning',
                    'icon'  => 'coupon',
                    'color' => 'orange',
                    'title' => $expiringCoupons . ' coupon' . ($expiringCoupons > 1 ? 's' : '') . ' expiring soon',
                    'sub'   => 'Expires within 3 days',
                    'link'  => '/admin/coupons',
                    'time'  => 'Soon',
                    'badge' => $expiringCoupons,
                ];
            }

            $total = count($notifications);
            $unread = $total; // count of alert types, not sum of items

            return response()->json([
                'success'       => true,
                'notifications' => $notifications,
                'total'         => $total,
                'unread'        => $unread,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success'       => true,
                'notifications' => [],
                'total'         => 0,
                'unread'        => 0,
            ]);
        }
    }
}
