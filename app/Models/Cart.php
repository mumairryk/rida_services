<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = "cart";
    protected $guarded = [];
    public static function get_user_cart($where)
    {
        return Cart::where($where)->orderby("id", "asc")->get();
    }

    public static function update_cart($data, $condition)
    {
        return Cart::where($condition)->update($data);
    }
    public static function create_cart($data)
    {
        $cart = Cart::create($data);
        if ($cart) {
            return $cart->id;
        } else {
            return 0;
        }
    }

    public static function get_cart_products($condition=[]){
        $where = [];
        $where['product.deleted'] = 0;
        $where['product.product_status'] = 1;
        ////$where['stores.deleted'] = 0;
        //$where['stores.active'] = 1;
        //$where['stores.verified'] = 1;
        $items = Cart::join('product','product.id','=','cart.product_id')
        ->join('users','users.id','=','product.product_vender_id')
        //->join('stores','stores.vendor_id','users.id')
        ->join('product_selected_attribute_list as pa','pa.product_attribute_id','=','cart.product_attribute_id')
        ->where($where)
        ->where($condition)
        ->select(['cart.*','users.name','pa.*','product.product_name','product.product_type',\DB::raw("(select string_agg(category_id::text,',')  from product_category where product_id=cart.product_id) category_selected")]);
        return $items->get();
    }

   
}
