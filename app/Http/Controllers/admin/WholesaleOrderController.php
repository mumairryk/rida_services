<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\WholesaleOrder;
use App\Models\WholesaleOrderItem;
use Illuminate\Http\Request;

class WholesaleOrderController extends Controller
{
    public function index()
    {
        if (!check_permission('orders', 'View')) {
            abort(404);
        }
        $page_heading = "Orders";
        $order_id = $_GET['order_id'] ?? '';
        $name = $_GET['name'] ?? '';
        $customer = $_GET['customer'] ?? '';
        $from = !empty($_GET['from']) ? date('Y-m-d', strtotime($_GET['from'])) : '';
        $to = !empty($_GET['to']) ? date('Y-m-d', strtotime($_GET['to'])) : '';
        $vendor = \request()->get('vendor');

        $list = WholesaleOrder::select('wholesale_orders.*', 'users.name')
            ->leftjoin('users', 'users.id', 'wholesale_orders.user_id')
            ->with(['customer' => function ($q) use ($name) {
                $q->where('name', 'like', '%' . $name . '%');
            }])->where('wholesale_orders.vendor_id', $vendor);
        if ($name) {
            $list = $list->whereRaw("name like '%" . $name . "%' ");
        }
        if ($order_id) {
            $list = $list->where('wholesale_orders.order_number', 'like', '%' . $order_id . '%');
        }
        if ($customer) {
            $list = $list->where('wholesale_orders.user_id', $customer);
        }
        if ($from) {
            $list = $list->whereDate('wholesale_orders.created_at', '>=', $from . ' 00:00:00');
        }
        if ($to) {
            $list = $list->where('wholesale_orders.created_at', '<=', $to . ' 23:59:59');
        }
        $list = $list->orderBy('wholesale_orders.id', 'DESC')->paginate(10);

        return view('admin.wholeSellers.order-list', compact('page_heading', 'list', 'order_id', 'name', 'from', 'to', 'vendor'));
    }

    public function details(Request $request, $id)
    {
        if (!check_permission('orders', 'Details')) {
            abort(404);
        }
        $page_heading = "Orders Details";
        $filter['order_id'] = $id;

        $page = (int) $request->page ?? 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $list = WholesaleOrderItem::get_order_details($filter)->skip($offset)->take($limit)->get();
        $list = process_order($list);
        // $highest_order_prd_status = WholesaleOrderItem::where('wholesale_order_id', $id)->orderby('order_status', 'desc')->first();
        $show_cancel = 0;
        // if (isset($highest_order_prd_status->order_status) && $highest_order_prd_status->order_status == 1) {
        //     $show_cancel = 1;
        // }
        return view('admin.wholeSellers.order-details', compact('page_heading', 'list', 'show_cancel'));
    }
    function order_change_status(Request $request)
    {
        $status  = "0";
        $message = "";
        $statusid =  isset($request->statusid)? $request->statusid : 0;
        if($request->detailsid){
            $update['status_id'] = $statusid;
            if(WholesaleOrder::where('id',$request->detailsid)->update($update)){
                $status = "1";
                $message = "Successfully updated";
            }else{
                $message = "Something went wrong";
            }
        }else{
            $message = "Something went wrong";
        }
        echo json_encode(['status'=>$status,'message'=>$message]);
    }
}
