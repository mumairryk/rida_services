<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WholesaleOrderItem extends Model
{
    use HasFactory;
    public static function get_order_details($filter = [])
    {
        $data = DB::table('wholesale_orders')
            ->leftjoin('users', 'users.id', 'wholesale_orders.user_id')
            ->select('wholesale_orders.*', 'users.name');
        if (!empty($filter['user_id'])) {
            $data->where('orders.user_id', $filter['user_id']);
        }
        if (!empty($filter['order_id'])) {
            $data->where('wholesale_orders.id', $filter['order_id']);
        }

        return $data;
    }
    public static function product_details($filter)
    {
        $data = DB::table('wholesale_order_items')
            ->select('wholesale_order_items.*', 'wholesale_order_items.quantity as order_qty', 'wholesale_order_items.price as order_price', 'wholesale_order_items.total_amount as order_total', 'menu_items.title', 'menu_items.image')
            ->where($filter)
            ->join("menu_items", "menu_items.id", "=", "wholesale_order_items.menu_item_id")            
            ->get();

        return $data;
    }
}
