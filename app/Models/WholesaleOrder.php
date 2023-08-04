<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WholesaleOrder extends Model
{
    use HasFactory;
    protected $table = 'wholesale_orders';
    protected $guarded = [];

    function customer()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    function items()
    {
        return $this->hasMany('App\Models\WholesaleOrderItem', 'wholesale_order_id');
    }

    function vendor()
    {
        return $this->belongsTo('App\Models\User', 'vendor_id');
    }

}
