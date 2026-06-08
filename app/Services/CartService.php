<?php

namespace App\Services;

use App\Repositories\CartRepository;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CartService
{
    protected $cartRepository;
    protected $guestCookieName = 'decathlon_guest_cart_id';

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function getCart()
    {
        // Check Customer Guard first (frontend users)
        if (Auth::guard('customer')->check()) {
            $customerId = Auth::guard('customer')->id();
            $cart = $this->cartRepository->getByCustomerId($customerId);
            if (!$cart) {
                $cart = $this->cartRepository->create(['customer_id' => $customerId]);
            }
            return $cart;
        }

        // Fallback to Web Guard (admin users etc.)
        if (Auth::check()) {
            $cart = $this->cartRepository->getByUserId(Auth::id());
            if (!$cart) {
                $cart = $this->cartRepository->create(['user_id' => Auth::id()]);
            }
            return $cart;
        }

        $guestId = Cookie::get($this->guestCookieName);
        if (!$guestId) {
            $guestId = (string) Str::uuid();
            Cookie::queue($this->guestCookieName, $guestId, 60 * 24 * 30); // 30 days
        }

        $cart = $this->cartRepository->getByGuestId($guestId);
        if (!$cart) {
            $cart = $this->cartRepository->create(['guest_id' => $guestId]);
        }

        return $cart;
    }

    public function add($productId, $variantId, $quantity = 1)
    {
        $product = Product::active()->findOrFail($productId);
        $variant = ProductVariant::findOrFail($variantId);

        // Validate stock if management is enabled
        if ($variant->manage_stock && $variant->stock_quantity < $quantity) {
            throw new \Exception("Insufficient stock for this product.");
        }

        $cart = $this->getCart();
        $item = $this->cartRepository->findItem($cart, $productId, $variantId);

        if ($item) {
            $newQuantity = $item->quantity + $quantity;
            if ($variant->manage_stock && $variant->stock_quantity < $newQuantity) {
                throw new \Exception("Insufficient stock for this product.");
            }
            $item->update(['quantity' => $newQuantity]);
        } else {
            $this->cartRepository->addItem($cart, $productId, $variantId, $quantity);
        }

        $this->cartRepository->updateLastActivity($cart);
        return $cart->fresh(['items.variant.variantAttributes.attributeValue', 'items.product.brand']);
    }

    public function update($itemId, $quantity)
    {
        $cart = $this->getCart();
        $item = $cart->items()->findOrFail($itemId);
        
        $variant = $item->variant;
        if ($variant->manage_stock && $variant->stock_quantity < $quantity) {
            throw new \Exception("Insufficient stock for this product.");
        }

        $item->update(['quantity' => $quantity]);
        $this->cartRepository->updateLastActivity($cart);
        
        return $cart->fresh(['items.variant.variantAttributes.attributeValue', 'items.product.brand']);
    }

    public function remove($itemId)
    {
        $cart = $this->getCart();
        $item = $cart->items()->findOrFail($itemId);
        $item->delete();
        
        $this->cartRepository->updateLastActivity($cart);
        return $cart->fresh(['items.variant.variantAttributes.attributeValue', 'items.product.brand']);
    }

    public function clear()
    {
        $cart = $this->getCart();
        $this->cartRepository->clear($cart);
        return $cart->fresh('items');
    }

    /**
     * Merge guest cart into customer cart after login.
     * @param $customer
     */
    public function mergeGuestCart($customer)
    {
        $guestId = Cookie::get($this->guestCookieName);
        if (!$guestId) return;

        $guestCart = $this->cartRepository->getByGuestId($guestId);
        if (!$guestCart || $guestCart->items->isEmpty()) {
            if ($guestCart) $guestCart->delete();
            return;
        }

        $customerCart = $this->cartRepository->getByCustomerId($customer->id);
        if (!$customerCart) {
            $customerCart = $this->cartRepository->create(['customer_id' => $customer->id]);
        }

        foreach ($guestCart->items as $gItem) {
            $existingItem = $this->cartRepository->findItem($customerCart, $gItem->product_id, $gItem->product_variant_id);
            
            if ($existingItem) {
                $newQuantity = $existingItem->quantity + $gItem->quantity;
                $existingItem->update(['quantity' => $newQuantity]);
            } else {
                $gItem->update(['cart_id' => $customerCart->id]);
            }
        }

        $guestCart->delete();
        Cookie::queue(Cookie::forget($this->guestCookieName));
    }
}
