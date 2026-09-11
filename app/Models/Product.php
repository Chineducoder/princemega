<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'brand_id', 'category_id', 'name', 'slug', 'subtitle', 'micro_label',
        'description', 'retail_price', 'wholesale_tiers', 'moq', 'pack_note',
        'shades', 'images', 'weight_kg', 'volume_m3', 'is_featured',
        'is_active', 'sort',
    ];

    protected function casts(): array
    {
        return [
            'wholesale_tiers' => 'array',
            'shades' => 'array',
            'images' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function hubs()
    {
        return $this->belongsToMany(Hub::class, 'hub_inventory')
            ->withPivot(['stock', 'low_stock_threshold'])
            ->withTimestamps();
    }

    public function getSubtitleAttribute(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return str_replace(['\u00b7', '\u2014', '\u2019', '\u00e9'], ['•', '—', '’', 'é'], $value);
    }

    /* -------------------------------------------------------------- */
    /* Pricing                                                        */
    /* -------------------------------------------------------------- */

    public function unitPrice(string $mode, int $quantity): int
    {
        if ($mode === 'wholesale') {
            return \App\Support\WholesaleTiers::resolve(
                $this->wholesale_tiers ?? [],
                $this->retail_price,
                $quantity,
            )['price'];
        }

        return $this->retail_price;
    }

    public function ladder(): array
    {
        return \App\Support\WholesaleTiers::ladder(
            $this->wholesale_tiers ?? [],
            $this->retail_price,
        );
    }

    /* -------------------------------------------------------------- */
    /* Availability — resolved against a hub's ledger                 */
    /* -------------------------------------------------------------- */

    public function stockAt(?Hub $hub): int
    {
        if (! $hub) {
            return (int) max($this->hubs->pluck('pivot.stock')->all());
        }

        $pivot = $this->hubs->firstWhere('id', $hub->id)?->pivot;

        return $pivot ? (int) $pivot->stock : 0;
    }

    public function availabilityAt(?Hub $hub, int $requested = 1): array
    {
        $stock = $this->stockAt($hub);
        $threshold = $this->hubs
            ->firstWhere('id', $hub?->id)?->pivot?->low_stock_threshold ?? 5;

        if ($stock <= 0) {
            return ['status' => 'out', 'stock' => 0, 'hub' => $hub?->short_code];
        }

        if ($stock < $requested || $stock <= $threshold) {
            return ['status' => 'low', 'stock' => $stock, 'hub' => $hub?->short_code];
        }

        return ['status' => 'in', 'stock' => $stock, 'hub' => $hub?->short_code];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
