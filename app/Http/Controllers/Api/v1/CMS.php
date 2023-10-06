<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Categories;
use App\Models\Cities;
use App\Models\CountryModel;
use App\Models\HelpModel;
use App\Models\States;
use App\Models\Services;
use App\Models\BannerModel;
use App\Models\ProductModel;
use DB;
use Illuminate\Http\Request;

class CMS extends Controller
{
    //
    public $lang = '';
    public function __construct(Request $request)
    {
        if (isset($request->lang)) {
            \App::setLocale($request->lang);
        }
        $this->lang = \App::getLocale();
    }
    public function countrylist(Request $request)
    {
        $countries = CountryModel::select('id','name','prefix','dial_code')->orderBy('name','asc')->where('active', 1)->get();
        return response()->json([
            'status' => "1",
            'message' => trans('validation.data_fetched_successfully'),
            'errors' => [],
            'oData' => $countries,
        ], 200);
    }

    public function states(Request $request)
    {
        $where['states.deleted'] = 0;
        $where['states.active'] = 1;
        if ($request->country_id) {
            $where['states.country_id'] = $request->country_id;
        }
        $states = States::select('id', 'name')->where($where)->orderby('name', 'asc')->get();
       
        return response()->json([
            'status' => "1",
            'message' => trans('validation.data_fetched_successfully'),
            'errors' => [],
            'oData' => $states,
        ], 200);
    }
    public function cities(Request $request)
    {
        $where['cities.deleted'] = 0;
        $where['cities.active'] = 1;
        if ($request->state_id) {
            $where['cities.state_id'] = $request->state_id;
        }
        $cities = Cities::select('id', 'name')->where($where)->orderby('name', 'asc')->get();
        return response()->json([
            'status' => "1",
            'message' => trans('validation.data_fetched_successfully'),
            'errors' => [],
            'oData' => $cities,
        ], 200);
    }
    public function get_page(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];

        $page_data = Article::where(['id' => $request->page_id])->get();
        if ($page_data->count() > 0) {
            $status = "1";
            $message = trans('validation.data_fetched_successfully');
            $o_data = $page_data->first();
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
            'errors' => [],
            'oData' => $o_data,
        ], 200);
    }
    public function get_faq(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];

        $page_data = \App\Models\FaqModel::orderBy('id', 'asc')->get();
        if ($page_data->count() > 0) {
            $status = "1";
            $message = trans('validation.data_fetched_successfully');
            $o_data = $page_data;
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
            'errors' => [],
            'oData' => $o_data,
        ], 200);
    }
    public function gethelp(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];

        $page_data = HelpModel::orderBy('id', 'asc')->get();
        if ($page_data->count() > 0) {
            $status = "1";
            $message = trans('validation.data_fetched_successfully');
            $o_data = $page_data;
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
            'errors' => [],
            'oData' => $o_data,
        ], 200);
    }
    public function categorylist(Request $request)
    {
        // $parent_id = 0;
        // $store_id = 0;
        // $list_type = 0;
        // if (isset($request->category_id) && $request->category_id) {
        //     $parent_id = $request->category_id;
        // }
        $categories = Categories::select('id', 'name','image','banner_image','slug')->orderBy('sort_order', 'asc')->where(['deleted' => 0, 'active' => 1])->get();
        return response()->json([
            'status' => "1",
            'message' => trans('validation.data_fetched_successfully'),
            'errors' => [],
            'oData' => $categories,
        ], 200);
    }
    public function home($division=null){
      $user = '';
      if($division){
            $banners = BannerModel::select('banner_image','product_id','category_id','division_id')->where('active',1)->where('type',1)->where('division_id',$division)->orderBy('created_at','desc')->get();
            $banners2 = BannerModel::select('banner_image','product_id','category_id','division_id')->where('active',1)->where('type',2)->where('division_id',$division)->orderBy('created_at','desc')->get();
            $banners3 = BannerModel::select('banner_image','product_id','category_id','division_id')->where('active',1)->where('type',3)->where('division_id',$division)->orderBy('created_at','desc')->get();


            $categories = Categories::select('id','name','image','banner_image')->where(['parent_id'=>0,'active'=>1,'deleted'=>0,'division_id'=>$division])->limit(6)->get();
        }
        else{
            $banners = BannerModel::select('banner_image','product_id','category_id','division_id')->where('active',1)->where('type',1)->orderBy('created_at','desc')->get();
            $banners2 = BannerModel::select('banner_image','product_id','category_id','division_id')->where('active',1)->where('type',2)->orderBy('created_at','desc')->get();
            $banners3 = BannerModel::select('banner_image','product_id','category_id','division_id')->where('active',1)->where('type',3)->orderBy('created_at','desc')->get();


            $categories = Categories::select('id','name','image','banner_image')->where(['parent_id'=>0,'active'=>1,'deleted'=>0])->limit(6)->get();      
        }
      


      $where['product.deleted'] = 0;
      $where['product_status'] = 1;

      $filter['search_text'] = '';

      $latestproductlist = ProductModel::products_list($where, $filter, 5, 0)->get();
      $latestproductlist = $this->product_inv($latestproductlist, $user);


      $newproductlist = ProductModel::products_list($where, $filter, 5, 0)->get();
      $newproductlist = $this->product_inv($newproductlist, $user);

     
        $o_data['banners'] = $banners;
        $o_data['category_list'] = $categories;
        $o_data['banners_2'] = $banners2;
        $o_data['banners_3'] = $banners3;
        $o_data['latest'] = $latestproductlist;
        $o_data['new'] = $newproductlist;
        $o_data['view'] = $newproductlist;

      

      return response()->json([
        'status' => "1",
        'message' => trans('validation.data_fetched_successfully'),
        'errors' => [],
        'o_data' => convert_all_elements_to_string($o_data),
    ], 200);
    }
    public function product_inv($products, $user,$from='')
    {
        $ids = [];
        $modified_products = [];
        $key = 0;
        foreach ($products as $val) {
            if(in_array($val->id, $ids)){
                continue;
            }
            $ids[] = $val->id;
            $modified_products[] = $val;
            
            $modified_products[$key]->is_liked = 0;
            if ($user) {
                $is_liked = ProductLikes::where(['product_id' => $val->id, 'user_id' => $user->id])->count();
                if ($is_liked) {
                    $modified_products[$key]->is_liked = 1;
                }
            }
            $det = [];
            if ($val->default_attribute_id) {
                $det = DB::table('product_selected_attribute_list')->select('product_attribute_id', 'stock_quantity', 'sale_price', 'regular_price', 'image')->where('product_id', $val->id)->where('product_attribute_id', $val->default_attribute_id)->first();
                if ($det) {
                   
                    $images = $det->image;
                    if ($images) {
                        $images = explode(',', $images);
                        $images = array_values(array_filter($images));
                        $i = 0;
                        $prd_img = [];
                        foreach ($images as $img) {
                            if ($img) {
                                $prd_img[$i] = url(config('global.upload_path') . '/' . config('global.product_image_upload_dir') . str_replace(' ', '%20', $img));
                                $i++;
                            }
                        }
                        $det->image = $prd_img;
                    } else {
                        $det->image = [];
                    }
                }

            } else {
                $det = DB::table('product_selected_attribute_list')->select('product_attribute_id', 'stock_quantity', 'sale_price', 'regular_price', 'image')->where('product_id', $val->id)->orderBy('product_attribute_id', 'DESC')->limit(1)->first();

                if ($det) {
                    
                    $images = $det->image;
                    if ($images) {
                        $images = explode(',', $images);
                        $images = array_values(array_filter($images));
                        $i = 0;
                        $prd_img = [];

                        foreach ($images as $img) {
                            if ($img) {
                                $prd_img[$i] = url(config('global.upload_path') . '/' . config('global.product_image_upload_dir') . str_replace(' ', '%20', $img));
                                $i++;
                            }
                        }
                        $det->image = $prd_img;
                    } else {
                        $det->image = [];
                    }
                }
            }
            $modified_products[$key]->inventory = $det;
            $key = $key+1;
        }
       
        return $modified_products;
        
    }
}
