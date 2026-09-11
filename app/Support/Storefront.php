<?php

namespace App\Support;

use App\Models\Hub;
use App\Models\Product;

/**
 * Storefront context — purchasing mode and physical hub selection,
 * persisted in the session and shared by every controller.
 */
class Storefront
{
    public static function mode(): string
    {
        $mode = session('shop.mode', 'retail');

        return in_array($mode, ['retail', 'wholesale'], true) ? $mode : 'retail';
    }

    public static function setMode(string $mode): string
    {
        $mode = in_array($mode, ['retail', 'wholesale'], true) ? $mode : 'retail';
        session(['shop.mode' => $mode]);

        return $mode;
    }

    public static function hubId(): ?int
    {
        $id = session('shop.hub');

        return $id ? (int) $id : null;
    }

    public static function setHub(?int $hubId): void
    {
        session(['shop.hub' => $hubId]);
    }

    /** The selected physical hub; "All Physical Hubs" resolves to null. */
    public static function hub(): ?Hub
    {
        $id = self::hubId();
        if (! $id) {
            return null;
        }

        return Hub::find($id);
    }

    /** Hub options for the selector, "All Physical Hubs" included. */
    public static function hubOptions(): array
    {
        return Hub::orderBy('sort')->get()->map(fn (Hub $hub) => [
            'id' => $hub->id,
            'name' => $hub->name,
            'short_code' => $hub->short_code,
            'is_default' => $hub->is_default,
        ])->all();
    }

    /** Availability map for a product set in one query pass. */
    public static function availabilityMap($products): array
    {
        $hub = self::hub();

        return $products->mapWithKeys(function (Product $product) use ($hub) {
            $stock = $product->stockAt($hub);

            return [$product->id => [
                'stock' => $stock,
                'status' => $stock <= 0 ? 'out' : ($stock <= 5 ? 'low' : 'in'),
                'hub' => $hub?->short_code ?? 'ALL HUBS',
            ]];
        })->all();
    }
}
