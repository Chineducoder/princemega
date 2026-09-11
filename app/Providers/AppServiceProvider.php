<?php

namespace App\Providers;

use App\View\Composers\CartComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer(['components.cart-drawer', 'checkout.index'], CartComposer::class);
        View::composer('components.header', function ($view) {
            $view->with('categories', \App\Models\Category::orderBy('sort')->get());
        });
    }
}
