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
     * Display the checkout page (works for guest + logged-in).
     */
    public function index()
    {
        $cart = $this->cartService->getCart();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $customer = Auth::guard('customer')->user();
        $isGuest = !$customer;

        $addresses = collect();
        $defaultAddress = null;

        if ($customer) {
            $addresses = $customer->addresses;
            $defaultAddress = $addresses->where('is_default', true)->first() ?? $addresses->first();
        }

        return view('pages.checkout', compact('cart', 'addresses', 'defaultAddress', 'isGuest'));
    }

    /**
     * Store a new order (guest + logged-in).
     */
    public function store(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $isGuest = !$customer;

        // ── Validation ──
        $rules = [
            'payment_method' => 'required|in:cod,razorpay',
        ];

        if ($isGuest) {
            $rules['guest_name'] = 'required|string|max:255';
            $rules['guest_email'] = 'required|email|max:255';
            $rules['guest_phone'] = 'required|string|max:20';
        }

        $rules['address_id'] = 'required_without:new_address';
        $rules['new_address.full_name'] = 'required_if:address_id,new|required_if:is_guest,1|string|max:255';
        $rules['new_address.phone'] = 'required_if:address_id,new|required_if:is_guest,1|string|max:20';
        $rules['new_address.address_line1'] = 'required_if:address_id,new|required_if:is_guest,1|string|max:500';
        $rules['new_address.city'] = 'required_if:address_id,new|required_if:is_guest,1|string|max:255';
        $rules['new_address.state'] = 'required_if:address_id,new|required_if:is_guest,1|string|max:255';
        $rules['new_address.pincode'] = 'required_if:address_id,new|required_if:is_guest,1|string|max:10';

        $request->validate($rules);

        $cart = $this->cartService->getCart();
        if ($cart->items->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Your cart is empty.'], 422);
        }

        try {
            DB::beginTransaction();

            // ── Resolve Address ──
            if ($isGuest || $request->address_id === 'new') {
                $addr = $request->new_address;
                $shippingName    = $addr['full_name'];
                $shippingPhone   = $addr['phone'];
                $shippingAddress = $addr['address_line1'] . (!empty($addr['address_line2']) ? ', ' . $addr['address_line2'] : '');
                $shippingCity    = $addr['city'];
                $shippingState   = $addr['state'];
                $shippingPincode = $addr['pincode'];
                $shippingCountry = $addr['country'] ?? 'India';

                // Save address for logged-in customer
                if ($customer && $request->address_id === 'new') {
                    $customer->addresses()->create($request->new_address);
                }
            } else {
                $address = CustomerAddress::findOrFail($request->address_id);
                $shippingName    = $address->full_name;
                $shippingPhone   = $address->phone;
                $shippingAddress = $address->address_line1 . ($address->address_line2 ? ', ' . $address->address_line2 : '');
                $shippingCity    = $address->city;
                $shippingState   = $address->state;
                $shippingPincode = $address->pincode;
                $shippingCountry = $address->country;
            }

            // ── Resolve Customer Info ──
            if ($isGuest) {
                $customerName  = $request->guest_name;
                $customerEmail = $request->guest_email;
                $customerPhone = $request->guest_phone;
                $customerId    = null;
            } else {
                $customerName  = $customer->name;
                $customerEmail = $customer->email;
                $customerPhone = $customer->phone ?? $shippingPhone;
                $customerId    = $customer->id;
            }

            // ── Create Order ──
            $order = new Order();
            $order->order_number    = Order::generateOrderNumber();
            $order->status          = 'pending';
            $order->payment_status  = 'pending';
            $order->payment_method  = $request->payment_method;

            $order->customer_id     = $customerId;
            $order->customer_name   = $customerName;
            $order->customer_email  = $customerEmail;
            $order->customer_phone  = $customerPhone;

            $order->shipping_name    = $shippingName;
            $order->shipping_address = $shippingAddress;
            $order->shipping_city    = $shippingCity;
            $order->shipping_state   = $shippingState;
            $order->shipping_pincode = $shippingPincode;
            $order->shipping_country = $shippingCountry;

            $order->billing_name    = $shippingName;
            $order->billing_address = $shippingAddress;
            $order->billing_city    = $shippingCity;
            $order->billing_state   = $shippingState;
            $order->billing_pincode = $shippingPincode;

            $order->subtotal        = $cart->total_amount;
            $order->shipping_amount = 0;
            $order->tax_amount      = 0;
            $order->total_amount    = $order->subtotal + $order->shipping_amount + $order->tax_amount;
            $order->source          = $isGuest ? 'guest_checkout' : 'website';
            $order->save();

            // ── Create Order Items ──
            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id'   => $item->product_id,
                    'variant_id'   => $item->product_variant_id,
                    'product_name' => $item->product->name,
                    'variant_name' => $item->variant?->variant_name,
                    'quantity'     => $item->quantity,
                    'unit_price'   => $item->variant?->price ?? 0,
                    'total_price'  => ($item->variant?->price ?? 0) * $item->quantity,
                ]);

                if ($item->variant?->manage_stock) {
                    $item->variant->decrement('stock_quantity', $item->quantity);
                }
            }

            // ── Clear Cart ──
            $this->cartService->clear();

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Order placed successfully!',
                'order_id' => $order->id,
                'redirect' => route('checkout.success', $order->order_number),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order. ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Order success page (guest + logged-in).
     */
    public function success($orderNumber)
    {
        $customer = Auth::guard('customer')->user();

        $query = Order::where('order_number', $orderNumber);

        if ($customer) {
            $query->where('customer_id', $customer->id);
        }
        // Guests can view by order number alone (no customer_id filter)

        $order = $query->firstOrFail();

        $isGuest = !$customer;

        return view('pages.checkout-success', compact('order', 'isGuest'));
    }

    /**
     * Guest order tracking page.
     */
    public function track()
    {
        return view('pages.order-track');
    }

    /**
     * Guest order tracking lookup.
     */
    public function trackLookup(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
            'email'        => 'required|email',
        ]);

        $order = Order::where('order_number', $request->order_number)
            ->where('customer_email', $request->email)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'No order found with that order number and email.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'order'   => [
                'order_number'  => $order->order_number,
                'status'        => $order->status,
                'payment_status'=> $order->payment_status,
                'payment_method'=> $order->payment_method,
                'total_amount'  => number_format($order->total_amount, 2),
                'created_at'    => $order->created_at->format('d M Y, h:i A'),
                'items'         => $order->items->map(fn($item) => [
                    'product_name' => $item->product_name,
                    'quantity'     => $item->quantity,
                    'total_price'  => number_format($item->total_price, 2),
                ]),
                'shipping_name'    => $order->shipping_name,
                'shipping_address' => $order->shipping_address,
                'shipping_city'    => $order->shipping_city,
                'shipping_state'   => $order->shipping_state,
                'shipping_pincode' => $order->shipping_pincode,
            ],
        ]);
    }
}
