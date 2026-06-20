<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CartService;
use App\Services\WishlistService;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;

class CartServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Event::listen(Login::class, function ($event) {
            $cartService = app(CartService::class);
            $cartService->mergeGuestCart($event->user);

            $wishlistService = app(WishlistService::class);
            $wishlistService->mergeGuestWishlist($event->user);
        });
    }
}
