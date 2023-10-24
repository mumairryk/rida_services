<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderModel;
use DB;
use App\Models\OrderProductsModel;
use Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $page_heading = "Vendor Dashboard";
        $store_id = Auth::user()->id;

        $latest_orders =  OrderModel::select('orders.*','users.name',DB::raw("CONCAT(users.first_name,' ',users.last_name) as customer_name"))->leftjoin('users','users.id','orders.user_id')->with(['customer'])->orderBy('orders.order_id','DESC')->with('customer')->leftJoinSub(
        OrderProductsModel::select('vendor_id', 'order_id')
            ->groupBy('order_id', 'vendor_id'),
        'order_products',
        'order_products.order_id',
        '=',
        'orders.order_id'
    )->where('vendor_id',$store_id)->limit(10)->get();
        foreach($latest_orders as $key => $val){
            $lowest_order_prd_status = OrderProductsModel::where('order_id', $val->order_id)->orderby('order_status', 'asc')->first();
                if (isset($lowest_order_prd_status->order_status)) {
                    $latest_orders[$key]->status = $lowest_order_prd_status->order_status;
                    $latest_orders[$key]->status_text = order_status($lowest_order_prd_status->order_status);
                } else {
                    $latest_orders[$key]->status_text = order_status($val->status);
                }
        }

        return view('vendor.dashboard', compact('page_heading','latest_orders'));
    }
}
