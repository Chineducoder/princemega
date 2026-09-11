<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'reference', 'session_id', 'hub_id', 'mode', 'subtotal', 'delivery_fee',
        'total', 'delivery_zone', 'delivery_method', 'destination_state',
        'destination_city', 'receipt_path', 'status',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function hub()
    {
        return $this->belongsTo(Hub::class);
    }
}
