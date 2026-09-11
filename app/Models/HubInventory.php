<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HubInventory extends Model
{
    protected $table = 'hub_inventory';

    protected $fillable = ['hub_id', 'product_id', 'stock', 'low_stock_threshold'];
}
