<?php

namespace App\Http\Controllers\Admin;
use App\Models\VendorModel;
use App\Models\ProductModel;
use App\Models\OrderModel;
use App\Models\OrderProductsModel;
use DB;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $page_heading = "Dashboard";
        $users  = 0;
        $vendors = VendorModel::where(['role'=>'3','users.deleted'=>'0'])->get()->count();
        $products = ProductModel::where(['product.deleted'=>0])->get()->count();

        $latest_orders =  OrderModel::select('orders.*','users.name',DB::raw("CONCAT(users.first_name,' ',users.last_name) as customer_name"))->leftjoin('users','users.id','orders.user_id')->with(['customer'])->orderBy('orders.order_id','DESC')->limit(10)->get();
        foreach($latest_orders as $key => $val){
            $lowest_order_prd_status = OrderProductsModel::where('order_id', $val->order_id)->orderby('order_status', 'asc')->first();
                if (isset($lowest_order_prd_status->order_status)) {
                    $latest_orders[$key]->status = $lowest_order_prd_status->order_status;
                    $latest_orders[$key]->status_text = order_status($lowest_order_prd_status->order_status);
                } else {
                    $latest_orders[$key]->status_text = order_status($val->status);
                }
        }
        // 
        $ready_for_delivery = OrderProductsModel::where('order_status',config('global.order_status_ready_for_delivery'))->join('orders','orders.order_id','order_products.order_id')->select('orders.created_at','orders.booking_date','orders.order_id','order_products.total','product_name',DB::raw("CONCAT(users.first_name,' ',users.last_name) as customer_name",'users.name'))->leftjoin('users','users.id','orders.user_id')->join('product', 'product.id', 'order_products.product_id')->limit(10)->get();


        $st_count['pending_count'] = OrderProductsModel::where('order_status',config('global.order_status_pending'))->where('is_returned',0)->get()->count();

        $st_count['accepted_count'] = OrderProductsModel::where('order_status',config('global.order_status_accepted'))->where('is_returned',0)->get()->count();

        $st_count['ready_for_delivery_count'] = OrderProductsModel::where('order_status',config('global.order_status_ready_for_delivery'))->where('is_returned',0)->get()->count();

        $st_count['dispatched_count'] = OrderProductsModel::where('order_status',config('global.order_status_dispatched'))->where('is_returned',0)->get()->count();

        $st_count['delivered_count'] = OrderProductsModel::where('order_status',config('global.order_status_delivered'))->where('is_returned',0)->get()->count();

        $st_count['cancelled_count'] = OrderProductsModel::where('order_status',config('global.order_status_cancelled'))->where('is_returned',0)->get()->count();

        $st_count['return_pending_count'] = OrderProductsModel::where('order_status',config('global.order_status_returned'))->where('is_returned',1)->where('ret_status',0)->get()->count();

        $st_count['returned_count'] = OrderProductsModel::where('order_status',config('global.order_status_returned'))->where('is_returned',1)->where('ret_status',1)->get()->count();

        $st_count['return_rejected_count'] = OrderProductsModel::where('order_status',config('global.order_status_returned'))->where('is_returned',1)->where('ret_status',2)->get()->count();
        

        $monthlyVendr = array('1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'7'=>0,'8'=>0,'9'=>0,'10'=>0,'11'=>0,'12'=>0);
        $year = gmdate('Y');
        foreach ($monthlyVendr as $key => $value) {
            $monthlyVendr[$key] = VendorModel::where(['role'=>'3','users.deleted'=>'0'])->whereRaw("(EXTRACT(MONTH from created_at) = {$key} AND (EXTRACT(YEAR from created_at) = {$year}))")->count();            
        }

        $last_7_days = $this->getLastNDays(7,'Y-m-d');
        $weeklyVendr = $this->getLastNDays(7,'N');
        $last_7_days_name = $this->getLastNDays(7,'l');
        // $weeklyDet = VendorModel::where(['role'=>'3','users.deleted'=>'0'])->where('created_at','>=',$last_7_days[0])->get();
        // dd($weeklyDet);
        foreach ($last_7_days as $key => $value) {
            $st = $value.' 00:00:00';
            $end = $value.' 23:59:59';
            $weeklyVendr[$key] =VendorModel::where(['role'=>'3','users.deleted'=>'0'])->where('created_at','>=',$st)->where('created_at','<=',$end)->count();
        }
        $cy = gmdate('Y');
        $yrs = [];
        $yr_array = [];
        for ($i = $cy-6; $i <= $cy; $i++) {
            $yrs[$i] = 0;
            array_push($yr_array,$i);
        }

        $yearlyVendr = $yrs;
        foreach ($yearlyVendr as $key => $value) {
            $yearlyVendr[$key] = VendorModel::where(['role'=>'3','users.deleted'=>'0'])->whereRaw("((EXTRACT(YEAR from created_at) = {$key}))")->count();
        }

        return view('admin.dashboard', compact('page_heading','vendors','products','latest_orders','ready_for_delivery','st_count','monthlyVendr','weeklyVendr','yearlyVendr','last_7_days_name'));
    }
    function getLastNDays($days, $format = 'd/m'){
        $m = gmdate("m"); $de= gmdate("d"); $y= gmdate("Y");
        $dateArray = array();
        for($i=0; $i<=$days-1; $i++){
            $dateArray[] =  gmdate($format, mktime(0,0,0,$m,($de-$i),$y)); 
        }
        return array_reverse($dateArray);
    }
}
