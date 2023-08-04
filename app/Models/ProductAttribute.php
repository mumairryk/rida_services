<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ProductAttribute extends Model
{
    //
    protected $table = "product_selected_attribute_list";
    protected $primaryKey = "product_attribute_id";
    public $timestamps = false;
     public $fillable = [
        'product_id',
        'manage_stock',
        'stock_quantity',
        'allow_back_order',
        'stock_status',
        'sale_price',
        'regular_price',
        'taxable',
        'image',
        'weight',
        'length',
        'width',
        'height',
        'shipping_note',
        'pr_code','product_desc','product_full_descr','barcode'
    ];
}