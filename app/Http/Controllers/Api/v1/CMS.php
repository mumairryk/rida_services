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
    public function home(){
      $banners = BannerModel::select('banner_image','product_id','category_id')->where('active',1)->orderBy('created_at','desc')->get();
      $services = Services::where(['services.deleted' => 0])
        // ->orderBy('services.created_at', 'asc')
        ->orderBy('sort_order', 'asc')
        ->get();

      return response()->json([
        'status' => "1",
        'message' => trans('validation.data_fetched_successfully'),
        'errors' => [],
        'banners' => $banners,
        'services' => $services,
    ], 200);
    }
}
