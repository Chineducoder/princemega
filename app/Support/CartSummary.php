<?php

namespace App\Support;

use App\Models\Cart;

/**
 * Single source of truth for cart serialization — consumed by the cart
 * controller endpoints and the view composer that hydrates the drawer.
 */
class CartSummary
{
    /** @return array{count:int, lines_count:int, subtotal:int, delivery:int, delivery_free:bool, delivery_reason:string, delivery_eta:string, total:int, currency:string, mode:string} */
    public static function build(Cart $cart, string $mode): array
    {
        $cart->load(['items.product.hubs']);

        $subtotal = (int) $cart->items->sum(fn ($item) => $item->lineTotal());

        $quote = DeliveryEngine::quote([
            'mode' => $mode,
            'hub_id' => $cart->hub_id ?? Storefront::hubId(),
            'method' => $cart->hub_id ? 'pickup' : 'delivery',
            'weight_kg' => $cart->items->sum(fn ($i) => $i->product->weight_kg * $i->quantity),
            'volume_m3' => $cart->items->sum(fn ($i) => $i->product->volume_m3 * $i->quantity),
            'subtotal' => $subtotal,
        ]);

        return [
            'count' => (int) $cart->items->sum('quantity'),
            'lines_count' => $cart->items->count(),
            'subtotal' => $subtotal,
            'delivery' => $quote['fee'],
            'delivery_free' => $quote['free'],
            'delivery_reason' => $quote['reason'],
            'delivery_eta' => $quote['eta'],
            'total' => $subtotal + $quote['fee'],
            'currency' => config('princemega.currency', 'NGN'),
            'mode' => $mode,
        ];
    }

    /** @return array<int, array{id:int, name:string, micro_label:string, shade:?string, mode:string, quantity:int, unit_price:int, line_total:int, image:?string}> */
    public static function lines(Cart $cart): array
    {
        $cart->load(['items.product']);

        return $cart->items->map(fn ($item) => [
            'id' => $item->id,
            'name' => $item->product->name,
            'micro_label' => $item->product->micro_label,
            'shade' => $item->shade,
            'mode' => $item->mode,
            'quantity' => (int) $item->quantity,
            'unit_price' => (int) $item->unit_price,
            'line_total' => $item->lineTotal(),
            'image' => $item->product->images[0]['src'] ?? null,
        ])->values()->all();
    }
}
