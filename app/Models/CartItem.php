<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id', 'product_id', 'shade', 'quantity', 'unit_price', 'mode',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function lineTotal(): int
    {
        return $this->unit_price * $this->quantity;
    }
}
