<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Product;
use App\Support\CartSummary;
use App\Support\Storefront;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:999'],
            'shade' => ['nullable', 'string', 'max:64'],
            'mode' => ['nullable', 'in:retail,wholesale'],
        ]);

        $mode = $validated['mode'] ?? Storefront::mode();
        $product = Product::active()->findOrFail($validated['product_id']);
        $cart = Cart::forSession($request->session()->getId());

        // Wholesale respects the MOQ floor; retail purchases freely.
        $quantity = (int) $validated['quantity'];
        if ($mode === 'wholesale') {
            $quantity = max($quantity, $product->moq);
        }

        $item = $cart->items()->updateOrCreate(
            [
                'product_id' => $product->id,
                'shade' => $validated['shade'] ?? null,
                'mode' => $mode,
            ],
            [
                'quantity' => $quantity,
                'unit_price' => $product->unitPrice($mode, $quantity),
            ],
        );

        return response()->json([
            'summary' => CartSummary::build($cart, $mode),
            'lines' => CartSummary::lines($cart),
        ]);
    }

    public function update(Request $request, CartItem $item): JsonResponse
    {
        abort_unless($item->cart->session_id === $request->session()->getId(), 404);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        $quantity = (int) $validated['quantity'];
        if ($item->mode === 'wholesale') {
            $quantity = max($quantity, $item->product->moq);
        }

        $item->update([
            'quantity' => $quantity,
            'unit_price' => $item->product->unitPrice($item->mode, $quantity),
        ]);

        return response()->json([
            'summary' => CartSummary::build($item->cart, $item->mode),
            'lines' => CartSummary::lines($item->cart),
        ]);
    }

    public function destroy(Request $request, CartItem $item): JsonResponse
    {
        abort_unless($item->cart->session_id === $request->session()->getId(), 404);

        $item->delete();

        return response()->json([
            'summary' => CartSummary::build($item->cart, Storefront::mode()),
            'lines' => CartSummary::lines($item->cart),
        ]);
    }

    public function summary(Request $request): JsonResponse
    {
        $cart = Cart::forSession($request->session()->getId());

        return response()->json(CartSummary::build($cart, Storefront::mode()));
    }
}
