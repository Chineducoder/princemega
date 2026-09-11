<?php

namespace App\Support;

/**
 * Wholesale pricing tiers.
 *
 * Tiers are declared as [min_quantity => unit_price]. The resolver walks
 * the ladder and returns the deepest tier the requested quantity unlocks,
 * together with a human description and the next-tier nudge so the
 * storefront can make the price progression visually obvious — in pure
 * typography, never colored badges.
 */
class WholesaleTiers
{
    /**
     * @param  array<int, int>  $tiers  min quantity => unit price (kobo-free naira)
     * @param  int  $retailPrice  fallback for the 1-unit rung
     */
    public static function ladder(array $tiers, int $retailPrice): array
    {
        if (! isset($tiers[1])) {
            $tiers[1] = $retailPrice;
        }
        ksort($tiers);

        $mins = array_keys($tiers);

        return collect($tiers)->values()->map(fn ($price, $i) => [
            'min' => (int) $mins[$i],
            'price' => (int) $price,
            'label' => self::rangeLabel((int) $mins[$i], $mins[$i + 1] ?? null),
        ])->all();
    }

    /**
     * The effective tier for a given quantity.
     *
     * @return array{min: int, price: int, label: string}
     */
    public static function resolve(array $tiers, int $retailPrice, int $quantity): array
    {
        $ladder = self::ladder($tiers, $retailPrice);

        $current = $ladder[0];
        foreach ($ladder as $rung) {
            if ($quantity >= $rung['min']) {
                $current = $rung;
            }
        }

        return $current;
    }

    /**
     * "1–5 units", "6–11 units", "12–23 units", "24+ units"
     */
    protected static function rangeLabel(int $min, ?int $nextMin): string
    {
        if ($nextMin === null) {
            return $min.'+ units';
        }

        return $min.'–'.($nextMin - 1).' units';
    }
}
