<?php

namespace App\View\Composers;

use App\Models\Cart;
use App\Support\CartSummary;
use App\Support\Storefront;
use Illuminate\View\View;

class CartComposer
{
    public function compose(View $view): void
    {
        $cart = Cart::forSession(session()->getId());

        $view->with('cart', [
            'summary' => CartSummary::build($cart, Storefront::mode()),
            'lines' => CartSummary::lines($cart),
        ]);
    }
}
