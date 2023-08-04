<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class MyModa extends Model
{
    use HasFactory;
    protected $table = "my_moda";
    protected $guarded = [];
    public static function get_moda_store_categories($store_id,$moda_sub_category,$where = [], $filter = [], $limit = '', $offset = 0){
        $where['product.deleted'] = 0;
        $where['product_status'] = 1;
        $where['product.moda_sub_category'] = $moda_sub_category;
        $where['product.store_id'] = $store_id;
        $categories = DB::table('product_category')->join('product','product.id','product_category.product_id')->join('category','category.id','product_category.category_id')->where($where)->select('category.id', 'category.name', 'category.image', 'category.banner_image')->groupby('category.id');     
        if (isset($filter['search_text']) && $filter['search_text']) {
            $srch = $filter['search_text'];
            $categories->whereRaw("(category.name ilike '%$srch%')");
        }
        if($limit !="") {
            $categories->limit($limit)->skip($offset);
        }
        $categories->orderBy('category.name', 'asc')->get();
        return $categories;
    }
}
