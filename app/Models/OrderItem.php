<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'name_snapshot', 'shade', 'quantity',
        'unit_price', 'line_total',
    ];
}
