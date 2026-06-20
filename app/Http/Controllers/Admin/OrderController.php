<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        return view('admin.pages.orders.index');
    }

    public function list(Request $request)
    {
        try {
            $query = Order::query();

            if ($request->search) {
                $s = $request->search;
                $query->where(function ($q) use ($s) {
                    $q->where('order_number', 'like', "%$s%")
                      ->orWhere('customer_name', 'like', "%$s%")
                      ->orWhere('customer_email', 'like', "%$s%")
                      ->orWhere('customer_phone', 'like', "%$s%");
                });
            }

            if ($request->status) {
                $query->where('status', $request->status);
            }

            if ($request->payment_status) {
                $query->where('payment_status', $request->payment_status);
            }

            if ($request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $orders = $query->withCount('items')
                ->orderByDesc('created_at')
                ->paginate(20);

            return response()->json([
                'success' => true,
                'data'    => $orders->items(),
                'meta'    => [
                    'total'        => $orders->total(),
                    'per_page'     => $orders->perPage(),
                    'current_page' => $orders->currentPage(),
                    'last_page'    => $orders->lastPage(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_email'   => 'required|email',
            'shipping_name'    => 'required|string|max:255',
            'shipping_address' => 'required|string',
            'shipping_city'    => 'required|string|max:100',
            'items'            => 'required|array|min:1',
            'items.*.product_id'   => 'required|integer',
            'items.*.product_name' => 'required|string',
            'items.*.quantity'     => 'required|integer|min:1',
            'items.*.unit_price'   => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += ($item['unit_price'] * $item['quantity']) - ($item['discount_amount'] ?? 0);
            }

            $shippingAmount = (float)($request->shipping_amount ?? 0);
            $taxAmount      = (float)($request->tax_amount ?? 0);
            $discountAmount = (float)($request->discount_amount ?? 0);
            $total          = $subtotal + $shippingAmount + $taxAmount - $discountAmount;

            $order = Order::create([
                'order_number'     => Order::generateOrderNumber(),
                'status'           => $request->status ?? 'pending',
                'payment_status'   => $request->payment_status ?? 'pending',
                'payment_method'   => $request->payment_method,
                'payment_reference'=> $request->payment_reference,
                'customer_name'    => $request->customer_name,
                'customer_email'   => $request->customer_email,
                'customer_phone'   => $request->customer_phone,
                'shipping_name'    => $request->shipping_name,
                'shipping_address' => $request->shipping_address,
                'shipping_city'    => $request->shipping_city,
                'shipping_state'   => $request->shipping_state,
                'shipping_pincode' => $request->shipping_pincode,
                'shipping_country' => $request->shipping_country ?? 'India',
                'billing_name'     => $request->billing_name,
                'billing_address'  => $request->billing_address,
                'billing_city'     => $request->billing_city,
                'billing_state'    => $request->billing_state,
                'billing_pincode'  => $request->billing_pincode,
                'subtotal'         => $subtotal,
                'discount_amount'  => $discountAmount,
                'shipping_amount'  => $shippingAmount,
                'tax_amount'       => $taxAmount,
                'total_amount'     => $total,
                'coupon_code'      => $request->coupon_code,
                'coupon_discount'  => $request->coupon_discount ?? 0,
                'shipping_method'  => $request->shipping_method,
                'customer_note'    => $request->customer_note,
                'admin_note'       => $request->admin_note,
                'source'           => 'manual',
                'created_by'       => session('admin_id'),
            ]);

            foreach ($request->items as $item) {
                $lineTotal = ($item['unit_price'] * $item['quantity']) - ($item['discount_amount'] ?? 0);
                OrderItem::create([
                    'order_id'        => $order->id,
                    'product_id'      => $item['product_id'],
                    'variant_id'      => $item['variant_id'] ?? null,
                    'product_name'    => $item['product_name'],
                    'product_sku'     => $item['product_sku'] ?? null,
                    'variant_name'    => $item['variant_name'] ?? null,
                    'product_image'   => $item['product_image'] ?? null,
                    'quantity'        => $item['quantity'],
                    'unit_price'      => $item['unit_price'],
                    'discount_amount' => $item['discount_amount'] ?? 0,
                    'tax_amount'      => $item['tax_amount'] ?? 0,
                    'total_price'     => $lineTotal,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data'    => $order->load('items'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $order = Order::with(['items', 'returns', 'invoice'])->findOrFail($id);
            return response()->json(['success' => true, 'data' => $order]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);

            $allowed = [
                'status', 'payment_status', 'payment_method', 'payment_reference',
                'tracking_number', 'shipping_carrier', 'shipping_method',
                'admin_note', 'customer_note',
                'shipped_at', 'delivered_at', 'estimated_delivery',
            ];

            $data = $request->only($allowed);

            // Auto-set timestamps
            if (isset($data['status'])) {
                if ($data['status'] === 'shipped' && !$order->shipped_at) {
                    $data['shipped_at'] = now();
                }
                if ($data['status'] === 'delivered' && !$order->delivered_at) {
                    $data['delivered_at'] = now();
                }
            }

            $order->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Order updated',
                'data'    => $order->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();
            return response()->json(['success' => true, 'message' => 'Order deleted']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);
            $oldStatus = $order->status;
            $newStatus = $request->status;

            \DB::beginTransaction();

            // Restore stock when cancelling an order
            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                $order->load('items.variant');
                foreach ($order->items as $item) {
                    if ($item->variant && $item->variant->manage_stock) {
                        $item->variant->increment('stock_quantity', $item->quantity);
                    }
                }
            }

            $order->update(['status' => $newStatus]);
            \DB::commit();

            return response()->json(['success' => true, 'message' => 'Status updated', 'data' => $order]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function stats()
    {
        try {
            $total     = Order::count();
            $pending   = Order::where('status', 'pending')->count();
            $processing= Order::where('status', 'processing')->count();
            $shipped   = Order::where('status', 'shipped')->count();
            $delivered = Order::where('status', 'delivered')->count();
            $cancelled = Order::where('status', 'cancelled')->count();
            $revenue   = Order::where('payment_status', 'paid')->sum('total_amount');
            $today     = Order::whereDate('created_at', today())->count();
            $thisMonth = Order::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();

            return response()->json(['success' => true, 'data' => [
                'total'      => $total,
                'pending'    => $pending,
                'processing' => $processing,
                'shipped'    => $shipped,
                'delivered'  => $delivered,
                'cancelled'  => $cancelled,
                'revenue'    => round($revenue, 2),
                'today'      => $today,
                'this_month' => $thisMonth,
            ]]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function searchProducts(Request $request)
    {
        try {
            $q = $request->q ?? '';
            $products = Product::where('status', 'active')
                ->where(function ($query) use ($q) {
                    $query->where('name', 'like', "%$q%")
                          ->orWhere('sku_prefix', 'like', "%$q%");
                })
                ->limit(10)
                ->get(['id', 'name', 'sku_prefix', 'price', 'sale_price', 'thumbnail']);

            return response()->json(['success' => true, 'data' => $products]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
