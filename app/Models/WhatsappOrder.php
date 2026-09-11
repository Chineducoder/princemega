<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappOrder extends Model
{
    protected $fillable = [
        'reference', 'order_id', 'mode', 'hub_id', 'summary', 'deep_link',
        'receipt_state',
    ];
}
