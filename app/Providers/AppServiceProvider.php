<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('categories')) {
            \Illuminate\Support\Facades\View::composer(['pages.*', 'layouts.app', 'partials.*'], \App\Http\ViewComposers\FrontendComposer::class);
        }
    }
}
