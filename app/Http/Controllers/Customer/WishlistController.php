<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $wishlist = Wishlist::where('customer_id', $customer->id)
            ->with([
                'product.featuredImage',
                'product.brand',
                'product.variants',
                'product.images',
            ])
            ->latest()
            ->get();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'wishlist' => $wishlist]);
        }

        $isMobile = request()->attributes->get('is_mobile');

        return view($isMobile ? 'customer.mobile.wishlist' : 'customer.desktop.wishlist', compact('wishlist'));
    }

    public function toggle(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $existing = Wishlist::where('customer_id', $customer->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['success' => true, 'status' => 'removed', 'message' => 'Removed from wishlist.']);
        }

        Wishlist::create([
            'customer_id' => $customer->id,
            'product_id' => $request->product_id,
        ]);

        return response()->json(['success' => true, 'status' => 'added', 'message' => 'Added to wishlist.']);
    }

    public function moveToCart(Request $request, string $id)
    {
        $customer = Auth::guard('customer')->user();

        $wishlistItem = Wishlist::where('customer_id', $customer->id)
            ->where('id', $id)
            ->with('product.variants')
            ->firstOrFail();

        $product = $wishlistItem->product;
        $variant = $product->variants()->first();

        $cart = $customer->cart()->firstOrCreate(['customer_id' => $customer->id]);

        $existingItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->where('product_variant_id', $variant?->id)
            ->first();

        if ($existingItem) {
            $existingItem->increment('quantity');
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'product_variant_id' => $variant?->id,
                'quantity' => 1,
            ]);
        }

        $wishlistItem->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Moved to cart successfully.']);
        }

        return redirect()->route('customer.wishlist')->with('success', 'Moved to cart successfully.');
    }

    public function destroy(Request $request, string $id)
    {
        $customer = Auth::guard('customer')->user();

        Wishlist::where('customer_id', $customer->id)
            ->where('id', $id)
            ->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Removed from wishlist.']);
        }

        return redirect()->route('customer.wishlist')->with('success', 'Removed from wishlist.');
    }
}
