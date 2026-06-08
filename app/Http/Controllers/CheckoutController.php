<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Models\CustomerAddress;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Display the checkout page.
     */
    public function index()
    {
        $cart = $this->cartService->getCart();
        
        if ($cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $customer = Auth::guard('customer')->user();
        $addresses = $customer->addresses;
        $defaultAddress = $addresses->where('is_default', true)->first() ?? $addresses->first();

        return view('pages.checkout', compact('cart', 'addresses', 'defaultAddress'));
    }

    /**
     * Store a new order.
     */
    public function store(Request $request)
    {
        $request->validate([
            'address_id' => 'required_without:new_address|exists:customer_addresses,id',
            'payment_method' => 'required|in:cod,razorpay',
            // If new address is provided
            'new_address.full_name' => 'required_if:address_id,new|string|max:255',
            'new_address.phone' => 'required_if:address_id,new|string|max:20',
            'new_address.address_line1' => 'required_if:address_id,new|string|max:500',
            'new_address.city' => 'required_if:address_id,new|string|max:255',
            'new_address.state' => 'required_if:address_id,new|string|max:255',
            'new_address.pincode' => 'required_if:address_id,new|string|max:10',
        ]);

        $cart = $this->cartService->getCart();
        if ($cart->items->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Your cart is empty.'], 422);
        }

        try {
            DB::beginTransaction();

            $customer = Auth::guard('customer')->user();
            
            // Get or Create Address
            if ($request->address_id === 'new') {
                $address = $customer->addresses()->create($request->new_address);
            } else {
                $address = CustomerAddress::findOrFail($request->address_id);
            }

            // Create Order
            $order = new Order();
            $order->order_number = Order::generateOrderNumber();
            $order->status = 'pending';
            $order->payment_status = 'pending';
            $order->payment_method = $request->payment_method;
            
            $order->customer_id = $customer->id;
            $order->customer_name = $customer->name;
            $order->customer_email = $customer->email;
            $order->customer_phone = $customer->phone ?? $address->phone;

            $order->shipping_name = $address->full_name;
            $order->shipping_address = $address->address_line1 . ($address->address_line2 ? ', ' . $address->address_line2 : '');
            $order->shipping_city = $address->city;
            $order->shipping_state = $address->state;
            $order->shipping_pincode = $address->pincode;
            $order->shipping_country = $address->country;

            // Simple billing address (same as shipping for now)
            $order->billing_name = $order->shipping_name;
            $order->billing_address = $order->shipping_address;
            $order->billing_city = $order->shipping_city;
            $order->billing_state = $order->shipping_state;
            $order->billing_pincode = $order->shipping_pincode;

            $order->subtotal = $cart->total_amount;
            $order->shipping_amount = 0; // FREE SHIPPING for now
            $order->tax_amount = 0; // Tax included in price for now
            $order->total_amount = $order->subtotal + $order->shipping_amount + $order->tax_amount;
            
            $order->source = 'website';
            $order->save();

            // Create Order Items
            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'variant_id' => $item->product_variant_id,
                    'product_name' => $item->product->name,
                    'variant_name' => $item->variant?->variant_name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->variant?->price ?? 0,
                    'total_price' => ($item->variant?->price ?? 0) * $item->quantity,
                ]);

                // Update stock if managed
                if ($item->variant?->manage_stock) {
                    $item->variant->decrement('stock_quantity', $item->quantity);
                }
            }

            // Clear Cart
            $this->cartService->clear();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_id' => $order->id,
                'redirect' => route('checkout.success', $order->order_number)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order. ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the order success page.
     */
    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('customer_id', Auth::guard('customer')->id())
            ->firstOrFail();

        return view('pages.checkout-success', compact('order'));
    }
}
