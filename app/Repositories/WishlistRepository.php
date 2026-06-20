<?php

namespace App\Repositories;

use App\Models\Wishlist;

class WishlistRepository
{
    public function getByCustomerId($customerId)
    {
        return Wishlist::where('customer_id', $customerId)
            ->with('product.brand', 'product.variants', 'product.images')
            ->get();
    }

    public function getByGuestId($guestId)
    {
        return Wishlist::where('guest_id', $guestId)
            ->with('product.brand', 'product.variants', 'product.images')
            ->get();
    }

    public function findByCustomerAndProduct($customerId, $productId)
    {
        return Wishlist::where('customer_id', $customerId)
            ->where('product_id', $productId)
            ->first();
    }

    public function findByGuestAndProduct($guestId, $productId)
    {
        return Wishlist::where('guest_id', $guestId)
            ->where('product_id', $productId)
            ->first();
    }

    public function create($customerId, $productId, $guestId = null)
    {
        return Wishlist::create([
            'customer_id' => $customerId,
            'guest_id' => $guestId,
            'product_id' => $productId
        ]);
    }

    public function remove($customerId, $productId)
    {
        return Wishlist::where('customer_id', $customerId)
            ->where('product_id', $productId)
            ->delete();
    }

    public function removeByGuestAndProduct($guestId, $productId)
    {
        return Wishlist::where('guest_id', $guestId)
            ->where('product_id', $productId)
            ->delete();
    }

    public function getCustomerProductIds($customerId)
    {
        return Wishlist::where('customer_id', $customerId)
            ->pluck('product_id')
            ->toArray();
    }

    public function getGuestProductIds($guestId)
    {
        return Wishlist::where('guest_id', $guestId)
            ->pluck('product_id')
            ->toArray();
    }

    public function isProductWishlisted($customerId, $productId)
    {
        return Wishlist::where('customer_id', $customerId)
            ->where('product_id', $productId)
            ->exists();
    }

    public function isProductWishlistedByGuest($guestId, $productId)
    {
        return Wishlist::where('guest_id', $guestId)
            ->where('product_id', $productId)
            ->exists();
    }

    public function countByCustomerId($customerId)
    {
        return Wishlist::where('customer_id', $customerId)->count();
    }

    public function countByGuestId($guestId)
    {
        return Wishlist::where('guest_id', $guestId)->count();
    }

    public function mergeGuestWishlist($guestId, $customerId)
    {
        $guestItems = Wishlist::where('guest_id', $guestId)->get();
        $added = 0;

        foreach ($guestItems as $item) {
            $exists = Wishlist::where('customer_id', $customerId)
                ->where('product_id', $item->product_id)
                ->exists();

            if (!$exists) {
                $item->update([
                    'customer_id' => $customerId,
                    'guest_id' => null,
                ]);
                $added++;
            } else {
                $item->delete();
            }
        }

        return $added;
    }
}
