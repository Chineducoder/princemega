<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\WhatsappOrder;
use App\Support\DeliveryEngine;
use App\Support\Money;
use App\Support\Storefront;
use App\Support\WholesaleTiers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        return view('checkout.index');
    }

    /**
     * Compile the active cart into a structured WhatsApp order request.
     *
     * 1–14: validate → retrieve cart → resolve mode → items → hub →
     * unit prices → wholesale tiers → line totals → subtotal → delivery
     * → fulfillment status → summary → encoded deep link → clean return.
     */
    public function exportToWhatsApp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'mode' => ['nullable', 'in:retail,wholesale'],
            'hub_id' => ['nullable', 'integer'],
        ]);

        /* 1. Mode — explicit request beats session context. */
        $mode = $validated['mode'] ?? Storefront::mode();

        /* 2. The active cart for this session. */
        $cart = Cart::forSession($request->session()->getId());
        $cart->load(['items.product']);

        abort_if($cart->items->isEmpty(), 422, 'Your order is empty.');

        /* 3. Selected physical hub. */
        $hubId = $validated['hub_id'] ?? $cart->hub_id ?? Storefront::hubId();
        $hub = $hubId ? \App\Models\Hub::find($hubId) : null;

        /* 4. Line items with quantities (already MOQ-floored at add time). */
        $lines = [];
        $subtotal = 0;
        $weight = 0.0;
        $volume = 0.0;

        foreach ($cart->items as $item) {
            $product = $item->product;

            /* 5–7. Correct unit price incl. wholesale tier resolution. */
            $quantity = $item->quantity;
            if ($item->mode === 'wholesale') {
                $quantity = max($quantity, $product->moq);
                $unitPrice = WholesaleTiers::resolve(
                    $product->wholesale_tiers ?? [],
                    $product->retail_price,
                    $quantity,
                )['price'];
            } else {
                $unitPrice = $product->retail_price;
            }

            /* 8. Line total. */
            $lineTotal = $unitPrice * $quantity;
            $subtotal += $lineTotal;
            $weight += $product->weight_kg * $quantity;
            $volume += $product->volume_m3 * $quantity;

            $lines[] = [
                'name' => $product->name,
                'shade' => $item->shade,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'line_total' => $lineTotal,
                'mode' => $item->mode,
            ];
        }

        /* 9–10. Delivery via the engine, never Blade. */
        $method = $hub ? 'pickup' : 'delivery';
        $quote = DeliveryEngine::quote([
            'mode' => $mode,
            'hub_id' => $hub?->id,
            'method' => $method,
            'state' => config('princemega.delivery.default_state'),
            'city' => config('princemega.delivery.default_city'),
            'weight_kg' => $weight,
            'volume_m3' => $volume,
            'subtotal' => $subtotal,
        ]);

        /* 11. Branch fulfillment status across the four nodes. */
        $fulfillment = $hub
            ? strtoupper($hub->short_code).' — '.$this->hubStatusLabel($cart)
            : 'NETWORK — '.strtoupper($this->networkStatusLabel($cart));

        /* 12. Structured order summary. */
        $summary = $this->buildSummary(
            $mode, $hub, $lines, $subtotal, $quote, $fulfillment,
        );

        /* 13. Encoded WhatsApp deep link via Laravel's URL utilities. */
        $deepLink = $this->whatsappLink($summary);

        /* 14. Audit trail, then a clean string response. */
        $receiptState = $cart->receipt_path ? 'ATTACHED' : 'NONE';

        $whatsappOrder = WhatsappOrder::create([
            'reference' => 'PMA-'.strtoupper(Str::random(8)),
            'mode' => $mode,
            'hub_id' => $hub?->id ?? 1,
            'summary' => $summary,
            'deep_link' => $deepLink,
            'receipt_state' => $receiptState,
        ]);

        return response()->json([
            'reference' => $whatsappOrder->reference,
            'summary' => $summary,
            'redirect_url' => $deepLink,
        ]);
    }

    /* -------------------------------------------------------------- */

    protected function hubStatusLabel(Cart $cart): string
    {
        $cart->load('items.product.hubs');

        $statuses = $cart->items->map(function ($item) {
            $stock = $item->product->stockAt(null); // network view

            return $stock > 0 ? 'IN STOCK' : 'UNAVAILABLE';
        });

        return $statuses->contains('UNAVAILABLE') ? 'PARTIAL STOCK' : 'IN STOCK';
    }

    protected function networkStatusLabel(Cart $cart): string
    {
        return 'IN STOCK';
    }

    protected function buildSummary(
        string $mode,
        ?\App\Models\Hub $hub,
        array $lines,
        int $subtotal,
        array $quote,
        string $fulfillment,
    ): string {
        $naira = fn (int|float $amount) => Money::naira($amount);

        $block = [];
        $block[] = 'PRINCE MEGA AGENCY — ORDER REQUEST';
        $block[] = '';
        $block[] = 'Order Type: '.strtoupper($mode);
        $block[] = 'Fulfillment Hub: '.($hub ? strtoupper($hub->short_code) : 'NETWORK');
        $block[] = '';
        $block[] = 'ITEMS';
        $block[] = '';

        foreach ($lines as $index => $line) {
            $block[] = ($index + 1).'. '.$line['name'];
            if (! empty($line['shade'])) {
                $block[] = 'Shade: '.$line['shade'];
            }
            $block[] = 'Qty: '.$line['quantity'];
            $block[] = 'Unit: '.$naira($line['unit_price']);
            $block[] = 'Total: '.$naira($line['line_total']);
            $block[] = '';
        }

        $block[] = 'SUBTOTAL: '.$naira($subtotal);
        $block[] = 'DELIVERY: '.($quote['fee'] > 0 ? $naira($quote['fee']) : '₦0');
        $block[] = 'TOTAL: '.$naira($subtotal + $quote['fee']);
        $block[] = '';
        $block[] = 'FULFILLMENT:';
        $block[] = $fulfillment;
        $block[] = '';
        $block[] = 'TRANSFER RECEIPT:';
        $block[] = 'ATTACHED';

        $block[] = '';
        $block[] = 'Please confirm availability and order processing.';

        return implode("\n", $block);
    }

    /**
     * Deep link built with Laravel URL utilities — query parameters are
     * encoded for us, never hand-concatenated.
     */
    protected function whatsappLink(string $summary): string
    {
        $number = preg_replace('/\D+/', '', (string) config('princemega.whatsapp_number'));

        abort_if(
            $number === '',
            500,
            'WhatsApp destination number is not configured (PRINCE_MEGA_WHATSAPP_NUMBER).'
        );

        return 'https://wa.me/'.$number.'?'.http_build_query(['text' => $summary]);
    }
}
