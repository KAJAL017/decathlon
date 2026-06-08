<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\CartService;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::listen(Login::class, function ($event) {
            $cartService = app(CartService::class);
            $cartService->mergeGuestCart($event->user);
        });
    }
}
