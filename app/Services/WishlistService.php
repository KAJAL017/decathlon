<?php

namespace App\Services;

use App\Repositories\WishlistRepository;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class WishlistService
{
    protected $wishlistRepository;
    protected $guestCookieName = 'decathlon_guest_wishlist_id';

    public function __construct(WishlistRepository $wishlistRepository)
    {
        $this->wishlistRepository = $wishlistRepository;
    }

    protected function getCustomerId()
    {
        if (Auth::guard('customer')->check()) {
            return Auth::guard('customer')->id();
        }
        return null;
    }

    protected function getGuestId()
    {
        $guestId = Cookie::get($this->guestCookieName);
        if (!$guestId) {
            $guestId = (string) Str::uuid();
            Cookie::queue($this->guestCookieName, $guestId, 60 * 24 * 30); // 30 days
        }
        return $guestId;
    }

    public function getWishlist()
    {
        $customerId = $this->getCustomerId();
        if ($customerId) {
            return $this->wishlistRepository->getByCustomerId($customerId);
        }

        $guestId = $this->getGuestId();
        return $this->wishlistRepository->getByGuestId($guestId);
    }

    public function toggle($productId)
    {
        Product::active()->findOrFail($productId);

        $customerId = $this->getCustomerId();
        $guestId = $this->getGuestId();

        if ($customerId) {
            $existing = $this->wishlistRepository->findByCustomerAndProduct($customerId, $productId);
            if ($existing) {
                $this->wishlistRepository->remove($customerId, $productId);
                return ['status' => 'removed', 'message' => 'Product removed from wishlist.'];
            }
            $this->wishlistRepository->create($customerId, $productId);
            return ['status' => 'added', 'message' => 'Product added to wishlist!'];
        }

        $existing = $this->wishlistRepository->findByGuestAndProduct($guestId, $productId);
        if ($existing) {
            $this->wishlistRepository->removeByGuestAndProduct($guestId, $productId);
            return ['status' => 'removed', 'message' => 'Product removed from wishlist.'];
        }
        $this->wishlistRepository->create(null, $productId, $guestId);
        return ['status' => 'added', 'message' => 'Product added to wishlist!'];
    }

    public function add($productId)
    {
        Product::active()->findOrFail($productId);

        $customerId = $this->getCustomerId();
        $guestId = $this->getGuestId();

        if ($customerId) {
            $existing = $this->wishlistRepository->findByCustomerAndProduct($customerId, $productId);
            if ($existing) {
                return ['status' => 'already_exists', 'message' => 'Product is already in your wishlist.'];
            }
            $this->wishlistRepository->create($customerId, $productId);
            return ['status' => 'added', 'message' => 'Product added to wishlist!'];
        }

        $existing = $this->wishlistRepository->findByGuestAndProduct($guestId, $productId);
        if ($existing) {
            return ['status' => 'already_exists', 'message' => 'Product is already in your wishlist.'];
        }
        $this->wishlistRepository->create(null, $productId, $guestId);
        return ['status' => 'added', 'message' => 'Product added to wishlist!'];
    }

    public function remove($productId)
    {
        $customerId = $this->getCustomerId();
        $guestId = $this->getGuestId();

        if ($customerId) {
            $this->wishlistRepository->remove($customerId, $productId);
            return ['status' => 'removed', 'message' => 'Product removed from wishlist.'];
        }

        $this->wishlistRepository->removeByGuestAndProduct($guestId, $productId);
        return ['status' => 'removed', 'message' => 'Product removed from wishlist.'];
    }

    public function getCount()
    {
        $customerId = $this->getCustomerId();
        if ($customerId) {
            return $this->wishlistRepository->countByCustomerId($customerId);
        }

        $guestId = $this->getGuestId();
        return $this->wishlistRepository->countByGuestId($guestId);
    }

    public function getCustomerProductIds()
    {
        $customerId = $this->getCustomerId();
        if ($customerId) {
            return $this->wishlistRepository->getCustomerProductIds($customerId);
        }

        $guestId = $this->getGuestId();
        return $this->wishlistRepository->getGuestProductIds($guestId);
    }

    public function isProductWishlisted($productId)
    {
        $customerId = $this->getCustomerId();
        if ($customerId) {
            return $this->wishlistRepository->isProductWishlisted($customerId, $productId);
        }

        $guestId = $this->getGuestId();
        return $this->wishlistRepository->isProductWishlistedByGuest($guestId, $productId);
    }

    public function mergeGuestWishlist($customer)
    {
        $guestId = Cookie::get($this->guestCookieName);
        if (!$guestId) return 0;

        $added = $this->wishlistRepository->mergeGuestWishlist($guestId, $customer->id);

        Cookie::queue(Cookie::forget($this->guestCookieName));

        return $added;
    }
}
