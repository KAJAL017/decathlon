<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Return_;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $status = $request->get('status', 'all');

        $query = $customer->orders()->with(['items.product', 'items.product.featuredImage']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->latest()->paginate(10);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'orders' => $orders]);
        }

        $isMobile = request()->attributes->get('is_mobile');

        return view($isMobile ? 'customer.mobile.orders.index' : 'customer.desktop.orders.index', compact('orders', 'status'));
    }

    public function show(Request $request, string $orderNumber)
    {
        $customer = Auth::guard('customer')->user();

        $order = $customer->orders()
            ->with([
                'items.product.featuredImage',
                'items.product.brand',
                'invoice',
                'returns',
            ])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'order' => $order]);
        }

        $isMobile = request()->attributes->get('is_mobile');

        return view($isMobile ? 'customer.mobile.orders.show' : 'customer.desktop.orders.show', compact('order'));
    }

    public function cancel(Request $request, string $orderNumber)
    {
        $customer = Auth::guard('customer')->user();

        $order = $customer->orders()
            ->with('items.variant')
            ->where('order_number', $orderNumber)
            ->whereIn('status', ['pending', 'confirmed'])
            ->firstOrFail();

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        \DB::beginTransaction();

        try {
            // Restore stock for each item
            foreach ($order->items as $item) {
                if ($item->variant && $item->variant->manage_stock) {
                    $item->variant->increment('stock_quantity', $item->quantity);
                }
            }

            $order->update([
                'status' => 'cancelled',
                'customer_note' => ($order->customer_note ? $order->customer_note . "\n" : '') . "[Cancelled by customer] Reason: " . $request->reason,
            ]);

            \DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Order cancelled successfully.']);
            }

            return redirect()->route('customer.orders.show', $orderNumber)
                ->with('success', 'Order cancelled successfully.');

        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }

    public function returnOrder(Request $request, string $orderNumber)
    {
        $customer = Auth::guard('customer')->user();

        $order = $customer->orders()
            ->where('order_number', $orderNumber)
            ->where('status', 'delivered')
            ->firstOrFail();

        $request->validate([
            'reason' => 'required|in:damaged,wrong_item,not_as_described,changed_mind,size_issue,other',
            'description' => 'required|string|max:1000',
            'type' => 'required|in:return,exchange,refund_only',
        ]);

        $return = Return_::create([
            'return_number' => Return_::generateReturnNumber(),
            'order_id' => $order->id,
            'status' => 'requested',
            'type' => $request->type,
            'reason' => $request->reason,
            'description' => $request->description,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Return request submitted successfully.']);
        }

        return redirect()->route('customer.orders.show', $orderNumber)
            ->with('success', 'Return request submitted successfully.');
    }

    public function track(Request $request, string $orderNumber)
    {
        $customer = Auth::guard('customer')->user();

        $order = $customer->orders()
            ->with(['items.product.featuredImage', 'items.product'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $trackingSteps = [
            ['key' => 'confirmed', 'label' => 'Order Confirmed', 'icon' => 'check-circle'],
            ['key' => 'processing', 'label' => 'Processing', 'icon' => 'package'],
            ['key' => 'shipped', 'label' => 'Shipped', 'icon' => 'truck'],
            ['key' => 'out_for_delivery', 'label' => 'Out for Delivery', 'icon' => 'map-pin'],
            ['key' => 'delivered', 'label' => 'Delivered', 'icon' => 'home'],
        ];

        $statusOrder = ['confirmed', 'processing', 'shipped', 'out_for_delivery', 'delivered'];
        $currentIndex = array_search($order->status, $statusOrder);
        if ($currentIndex === false) $currentIndex = -1;

        if ($request->ajax()) {
            return response()->json(['success' => true, 'order' => $order, 'trackingSteps' => $trackingSteps, 'currentIndex' => $currentIndex]);
        }

        $isMobile = request()->attributes->get('is_mobile');

        return view($isMobile ? 'customer.mobile.orders.track' : 'customer.desktop.orders.track', compact('order', 'trackingSteps', 'currentIndex'));
    }

    public function invoice(Request $request, string $orderNumber)
    {
        $customer = Auth::guard('customer')->user();

        $order = $customer->orders()
            ->with(['items.product', 'items.product.brand', 'invoice'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'order' => $order]);
        }

        $isMobile = request()->attributes->get('is_mobile');

        return view($isMobile ? 'customer.mobile.orders.invoice' : 'customer.desktop.orders.invoice', compact('order'));
    }
}
