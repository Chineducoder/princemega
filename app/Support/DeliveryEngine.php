<?php

namespace App\Support;

/**
 * Delivery engine — quote calculation isolated from the cart.
 *
 * Quotes are computed from the dimensions of the request, never from
 * Blade templates. The matrix below is the Port Harcourt launch matrix:
 * in-town destinations are flat-fee, everything else is zoned. Wholesale
 * orders ship free by commercial policy; retail orders cross the
 * free-delivery threshold per config.
 */
class DeliveryEngine
{
    /**
     * Zone matrix. '*' is the fallback.
     */
    protected const ZONES = [
        'Port Harcourt' => ['fee' => 1500, 'zone' => 'PH CITY'],
        'Obio/Akpor' => ['fee' => 1500, 'zone' => 'PH CITY'],
        'Eleme' => ['fee' => 2500, 'zone' => 'RIVERS RING'],
        'Oyigbo' => ['fee' => 2500, 'zone' => 'RIVERS RING'],
        'Ikwerre' => ['fee' => 2500, 'zone' => 'RIVERS RING'],
        'Emohua' => ['fee' => 2500, 'zone' => 'RIVERS RING'],
        'Port Harcourt (Rivers)' => ['fee' => 1500, 'zone' => 'PH CITY'],
        'Uyo' => ['fee' => 4500, 'zone' => 'SOUTH-SOUTH'],
        'Aba' => ['fee' => 3500, 'zone' => 'NEIGHBOURING'],
        'Owerri' => ['fee' => 3500, 'zone' => 'NEIGHBOURING'],
        'Lagos' => ['fee' => 6500, 'zone' => 'INTERSTATE'],
        'Abuja' => ['fee' => 6500, 'zone' => 'INTERSTATE'],
    ];

    /**
     * @param  array{
     *     mode?: string,
     *     hub_id?: int|string|null,
     *     state?: string|null,
     *     city?: string|null,
     *     weight_kg?: float,
     *     volume_m3?: float,
     *     method?: string
     * } $context
     * @return array{
     *     method: string, fee: int, free: bool, eta: string,
     *     zone: string, reason: string
     * }
     */
    public static function quote(array $context): array
    {
        $mode = $context['mode'] ?? 'retail';
        $method = $context['method'] ?? (! empty($context['hub_id']) ? 'pickup' : 'delivery');

        // 1. Hub pickup — physical collection at any of the four nodes.
        if ($method === 'pickup') {
            return [
                'method' => 'pickup',
                'fee' => 0,
                'free' => true,
                'eta' => 'SAME DAY',
                'zone' => 'HUB PICKUP',
                'reason' => 'Collect at your selected hub — no courier leg.',
            ];
        }

        // 2. Wholesale policy — fulfillment logistics priced into unit tiers.
        if ($mode === 'wholesale') {
            return [
                'method' => 'delivery',
                'fee' => 0,
                'free' => true,
                'eta' => '2–4 WORKING DAYS',
                'zone' => 'WHOLESALE',
                'reason' => 'Wholesale deliveries route free within Port Harcourt.',
            ];
        }

        // 3. Zoned matrix — city match first, then state-level fallback.
        $city = $context['city'] ?? config('princemega.delivery.default_city');
        $zone = self::ZONES[$city] ?? self::ZONES[$context['state'] ?? ''] ?? self::ZONES['Port Harcourt'];

        $fee = (int) $zone['fee'];

        // 4. Heavy-freight adjustment — cargo beyond the courier rung.
        $weight = (float) ($context['weight_kg'] ?? 0);
        $volume = (float) ($context['volume_m3'] ?? 0);
        $bulk = $weight > 15 || $volume > 0.12;
        if ($bulk) {
            $fee += 2000;
        }

        // 5. Retail free-delivery threshold per config.
        $threshold = (int) config('princemega.delivery.free_threshold', 0);
        $crossed = $threshold > 0 && ($context['subtotal'] ?? 0) >= $threshold;

        return [
            'method' => 'delivery',
            'fee' => $crossed ? 0 : $fee,
            'free' => $crossed,
            'eta' => $zone['zone'] === 'PH CITY' ? '1–2 WORKING DAYS' : '2–4 WORKING DAYS',
            'zone' => $zone['zone'],
            'reason' => match (true) {
                $crossed => 'FREE — ORDER CROSSES ₦'.number_format($threshold),
                $bulk => 'BULK SURCHARGE APPLIED — HEAVY FREIGHT',
                default => strtoupper($zone['zone']).' RATE',
            },
        ];
    }

    /**
     * Destinations offered at checkout. Extend or replace from the
     * database without touching this class — it reads whatever is given.
     *
     * @return array<string, array{state: string, city: string}>
     */
    public static function destinations(): array
    {
        return [
            'Port Harcourt' => ['state' => 'Rivers', 'city' => 'Port Harcourt'],
            'Obio/Akpor' => ['state' => 'Rivers', 'city' => 'Obio/Akpor'],
            'Eleme' => ['state' => 'Rivers', 'city' => 'Eleme'],
            'Oyigbo' => ['state' => 'Rivers', 'city' => 'Oyigbo'],
            'Emohua' => ['state' => 'Rivers', 'city' => 'Emohua'],
            'Uyo' => ['state' => 'Akwa Ibom', 'city' => 'Uyo'],
            'Aba' => ['state' => 'Abia', 'city' => 'Aba'],
            'Owerri' => ['state' => 'Imo', 'city' => 'Owerri'],
            'Lagos' => ['state' => 'Lagos', 'city' => 'Lagos'],
            'Abuja' => ['state' => 'FCT', 'city' => 'Abuja'],
        ];
    }
}
