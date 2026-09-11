<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hub extends Model
{
    protected $fillable = [
        'name', 'slug', 'short_code', 'address', 'area', 'is_default', 'sort',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    public function inventory()
    {
        return $this->belongsToMany(Product::class, 'hub_inventory')
            ->withPivot(['stock', 'low_stock_threshold'])
            ->withTimestamps();
    }
}
