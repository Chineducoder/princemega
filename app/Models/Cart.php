<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['session_id', 'mode', 'hub_id'];

    public function items()
    {
        return $this->hasMany(CartItem::class)->oldest();
    }

    public static function forSession(string $sessionId): self
    {
        return static::firstOrCreate(
            ['session_id' => $sessionId],
            ['mode' => session('shop.mode', 'retail'), 'hub_id' => session('shop.hub')],
        );
    }
}
