<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\CartItem;
use Carbon\Carbon;

class CartRepository
{
    public function getByUserId($userId)
    {
        return Cart::where('user_id', $userId)->with('items.variant.variantAttributes.attributeValue', 'items.product.brand')->first();
    }

    public function getByCustomerId($customerId)
    {
        return Cart::where('customer_id', $customerId)->with('items.variant.variantAttributes.attributeValue', 'items.product.brand')->first();
    }

    public function getByGuestId($guestId)
    {
        return Cart::where('guest_id', $guestId)->with('items.variant.variantAttributes.attributeValue', 'items.product.brand')->first();
    }

    public function create($data)
    {
        $data['last_activity'] = Carbon::now();
        return Cart::create($data);
    }

    public function updateLastActivity(Cart $cart)
    {
        $cart->update(['last_activity' => Carbon::now()]);
    }

    public function findItem(Cart $cart, $productId, $variantId)
    {
        return $cart->items()->where('product_id', $productId)
                    ->where('product_variant_id', $variantId)
                    ->first();
    }

    public function addItem(Cart $cart, $productId, $variantId, $quantity)
    {
        return $cart->items()->create([
            'product_id' => $productId,
            'product_variant_id' => $variantId,
            'quantity' => $quantity
        ]);
    }

    public function removeItem($itemId)
    {
        return CartItem::destroy($itemId);
    }

    public function clear(Cart $cart)
    {
        return $cart->items()->delete();
    }
}
