<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();
        return view('pages.cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'required|exists:product_variants,id',
            'quantity'   => 'integer|min:1'
        ]);

        try {
            $cart = $this->cartService->add(
                $request->product_id,
                $request->variant_id,
                $request->quantity ?? 1
            );

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart!',
                'data'    => $this->getCartData($cart)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            $cart = $this->cartService->update($itemId, $request->quantity);
            return response()->json([
                'success' => true,
                'message' => 'Cart updated!',
                'data'    => $this->getCartData($cart)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function remove($itemId)
    {
        try {
            $cart = $this->cartService->remove($itemId);
            return response()->json([
                'success' => true,
                'message' => 'Item removed!',
                'data'    => $this->getCartData($cart)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function clear()
    {
        try {
            $cart = $this->cartService->clear();
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared!',
                'data'    => $this->getCartData($cart)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function miniCart()
    {
        $cart = $this->cartService->getCart();
        return response()->json([
            'success' => true,
            'data'    => $this->getCartData($cart)
        ]);
    }

    protected function getCartData($cart)
    {
        return [
            'total_quantity' => $cart->total_quantity,
            'total_amount'   => number_format($cart->total_amount, 2),
            'items'          => $cart->items->map(function($item) {
                $featuredImage = $item->product->featuredImage ?? $item->product->images->first();
                return [
                    'id'            => $item->id,
                    'product_name'  => $item->product->name,
                    'variant_name'  => $item->variant?->variant_name ?? 'Default',
                    'price'         => number_format($item->variant?->price ?? 0, 2),
                    'quantity'      => $item->quantity,
                    'subtotal'      => number_format($item->subtotal ?? 0, 2),
                    'image'         => $featuredImage?->image_url ?? 'https://images.unsplash.com/photo-1560362614-890275988ce7?w=400',
                    'slug'          => $item->product->slug
                ];
            }),
            'mini_cart_html' => view('partials.mini-cart-items', compact('cart'))->render(),
            'cart_items_html' => view('partials.cart-items', compact('cart'))->render(),
            'cart_summary_html' => view('partials.cart-summary', compact('cart'))->render()
        ];
    }
}
