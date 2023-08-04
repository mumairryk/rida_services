<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stores extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function vendor()
    {
        return $this->belongsTo('App\Models\VendorModel', 'vendor_id', 'id');
    }
    public function products()
    {
        return $this->hasMany('App\Models\ProductModel','store_id','id');
    }
    //********don't change this function.. 
    public static function get_stores($where = [], $filter = [], $limit = '', $offset = 0)
    {
        $lat = $filter['lat'];
        $long = $filter['long'];
        $distance =
            "6371 * acos (
            cos ( radians( CAST (stores.latitude AS double precision) ) )
            * cos( radians( CAST ({$lat} AS double precision) ) )
            * cos( radians( CAST ({$long} AS double precision) ) - radians( CAST (stores.longitude AS double precision) ) )
            + sin ( radians( CAST (stores.latitude AS double precision) ) )
            * sin( radians ( CAST ({$lat} AS double precision) ) )
        )";
        $stores = Stores::has('products', '>' , 0)->withCount('products')->where($where)->select('id', 'store_name', 'location', 'logo', 'cover_image','latitude','longitude')->selectRaw("$distance as distance");

        if (isset($filter['distance']) && $filter['distance']) {
            $filter_distance = $filter['distance'];
            $stores->whereRaw("$distance<=$filter_distance");
        }
        if (isset($filter['search_text']) && $filter['search_text']) {
            $srch = $filter['search_text'];
            $stores->whereRaw("(store_name ilike '%$srch%' OR location ilike '%$srch%')");
        }
        // $stores = DB::table('stores');
        if($limit !="") {
            $stores->limit($limit)->skip($offset);
        }
        $stores->orderBy('distance','asc')->get();


        return $stores;
        
    }

    public static function get_stores_api($where = [], $filter = [], $limit = '', $offset = 0)
    {
        $lat = $filter['lat'];
        $long = $filter['long'];
        $distance =
            "6371 * acos (
            cos ( radians( CAST (stores.latitude AS double precision) ) )
            * cos( radians( CAST ({$lat} AS double precision) ) )
            * cos( radians( CAST ({$long} AS double precision) ) - radians( CAST (stores.longitude AS double precision) ) )
            + sin ( radians( CAST (stores.latitude AS double precision) ) )
            * sin( radians ( CAST ({$lat} AS double precision) ) )
        )";
        $stores = Stores::leftjoin('product','product.store_id','stores.id')->where($where)->select('stores.id', 'stores.store_name', 'stores.location', 'stores.logo', 'stores.cover_image','stores.latitude','stores.longitude')->selectRaw("$distance as distance")->selectRaw(DB::raw("(select count(store_id) from product where product.store_id=stores.id and product.deleted=0 and product_status=1) as product_count"))->groupby('stores.id');     
        if (isset($filter['parent_category_id']) && $filter['parent_category_id']) {

            $sub_categories = [];
            if (isset($filter['sub_category']) && $filter['sub_category']) {
                $sub_categories = array_values($filter['sub_category']);
                if($sub_categories[0]=="all"){
                    $sub_categories = [];
                }
            }
            $child_categories = DB::table('category')->where(['deleted'=>0,'active'=>1,'parent_id'=>$filter['parent_category_id']]);
            if($sub_categories){
                $child_categories = $child_categories->whereIn('category.id',$sub_categories);
            }
            $child_categories = $child_categories->get()->toArray();

            if($child_categories){
                $child_categories = array_column($child_categories,'id');           
                $stores->join('product_category', 'product_category.product_id','product.id')->whereIn('product_category.category_id',$child_categories);
            }else{
                $stores->join('product_category', 'product_category.product_id','product.id')->where('product_category.category_id',$filter['parent_category_id']);
            }
        }
        if (isset($filter['distance']) && $filter['distance']) {
            $filter_distance = $filter['distance'];
            $stores->whereRaw("$distance<=$filter_distance");
        }
        if (isset($filter['search_text']) && $filter['search_text']) {
            $srch = $filter['search_text'];
            $stores->whereRaw("(stores.store_name ilike '%$srch%' OR location ilike '%$srch%')");
        }
        // $stores = DB::table('stores');
        if($limit !="") {
            $stores->limit($limit)->skip($offset);
        }
        // $stores->orderBy('distance','asc')->get();
        $stores->orderBy('stores.sort_order', 'asc')->get();


        return $stores;
        
    }

    public static function get_store_by_moda_category($moda_sub_category,$where = [], $filter = [], $limit = '', $offset = 0){
        $lat = $filter['lat'];
        $long = $filter['long'];
        $where['product.deleted'] = 0;
        $where['product_status'] = 1;
        $where['product.moda_sub_category'] = $moda_sub_category;
        $distance =
            "6371 * acos (
            cos ( radians( CAST (stores.latitude AS double precision) ) )
            * cos( radians( CAST ({$lat} AS double precision) ) )
            * cos( radians( CAST ({$long} AS double precision) ) - radians( CAST (stores.longitude AS double precision) ) )
            + sin ( radians( CAST (stores.latitude AS double precision) ) )
            * sin( radians ( CAST ({$lat} AS double precision) ) )
        )";
        $stores = Stores::join('product','product.store_id','stores.id')->where($where)->select('stores.id', 'stores.store_name', 'stores.location', 'stores.logo', 'stores.cover_image','stores.latitude','stores.longitude')->selectRaw("$distance as distance")->selectRaw(DB::raw("(select count(store_id) from product where product.store_id=stores.id and product.deleted=0 and product_status=1 and product.moda_sub_category=$moda_sub_category) as product_count"))->groupby('stores.id');     
        if (isset($filter['distance']) && $filter['distance']) {
            $filter_distance = $filter['distance'];
            $stores->whereRaw("$distance<=$filter_distance");
        }
        if (isset($filter['search_text']) && $filter['search_text']) {
            $srch = $filter['search_text'];
            $stores->whereRaw("(stores.store_name ilike '%$srch%' OR location ilike '%$srch%')");
        }
        if($limit !="") {
            $stores->limit($limit)->skip($offset);
        }
        $stores->orderBy('stores.sort_order', 'asc')->get();
        return $stores;
    }

    public static function get_fav_stores($where = [], $filter = [], $limit = '', $offset = 0)
    {
        $lat = $filter['lat'];
        $long = $filter['long'];
        $distance =
            "6371 * acos (
            cos ( radians( CAST (stores.latitude AS double precision) ) )
            * cos( radians( CAST ({$lat} AS double precision) ) )
            * cos( radians( CAST ({$long} AS double precision) ) - radians( CAST (stores.longitude AS double precision) ) )
            + sin ( radians( CAST (stores.latitude AS double precision) ) )
            * sin( radians ( CAST ({$lat} AS double precision) ) )
        )";
        $stores = DB::table('store_likes')->join('stores','stores.id','store_likes.store_id')->where($where)->select('stores.id', 'stores.store_name', 'stores.location', 'stores.logo', 'stores.cover_image','stores.latitude','stores.longitude')->selectRaw("$distance as distance")->selectRaw(DB::raw("(select count(store_id) from product where product.store_id=stores.id and product.deleted=0 and product_status=1) as product_count"));
        if($limit !="") {
            $stores->limit($limit)->skip($offset);
        }
        $stores->orderBy('store_likes.created_at','desc')->get();


        return $stores;
        
    }
    public static function sort_item($item=[]){
        if( !empty($item) ){
            DB::beginTransaction();
            try {
                    $i=0;
                    foreach( $item as $key ){
                        Stores::where('id', $key)
                            ->update(['sort_order' => $i]);
                        $i++;
                    }
                    DB::commit();
                return 1;
            } catch (\Exception $e) {
                DB::rollback();
                return 0;
            }
        }else{
            return 0;
        }
    }
}
