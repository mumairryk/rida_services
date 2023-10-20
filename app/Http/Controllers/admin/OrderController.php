<?php

namespace App\Http\Controllers\admin;

use App\Exports\ExportReports;
use App\Http\Controllers\Controller;
use App\Models\OrderModel;
use App\Models\OrderProductsModel;
use DB;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function __construct(Database $database)
    {
        $this->database = $database;
    }
    public function index(Request $request)
    {

        if (!check_permission('orders', 'View')) {
            abort(404);
        }
        $page_heading = "Orders";
        $order_id = $_GET['order_id'] ?? '';
        $name = $_GET['name'] ?? '';
        $customer = $_GET['customer'] ?? '';
        $vendor = $_GET['vendor'] ?? '';
        $from = !empty($_GET['from']) ? date('Y-m-d', strtotime($_GET['from'])) : '';
        $to = !empty($_GET['to']) ? date('Y-m-d', strtotime($_GET['to'])) : '';

        $list = OrderModel::select('orders.*', 'users.name', DB::raw("CONCAT(users.first_name,' ',users.last_name) as customer_name"),'vendor_id')->leftjoin('users', 'users.id', 'orders.user_id')->with(['customer' => function ($q) use ($name) {
            $q->where('display_name', 'like', '%' . $name . '%');
        }])->leftJoinSub(
        OrderProductsModel::select('vendor_id', 'order_id')
            ->groupBy('order_id', 'vendor_id'),
        'order_products',
        'order_products.order_id',
        '=',
        'orders.order_id'
    );
        if ($name) {
            $list = $list->whereRaw("concat(first_name, ' ', last_name) like '%" . $name . "%' ");
        }
        if ($order_id) {
            $orderid = substr($order_id, 12);
            
            $list = $list->where('orders.order_id', $orderid);
        }
        if ($customer) {
            $list = $list->where('orders.user_id', $customer);
        }
        if ($vendor) {
            $list = $list->where('order_products.vendor_id', $vendor);
        }
        if ($from) {
            $list = $list->whereDate('orders.created_at', '>=', $from . ' 00:00:00');
        }
        if ($to) {
            $list = $list->where('orders.created_at', '<=', $to . ' 23:59:59');
        }
        $list = $list->orderBy('orders.order_id', 'DESC')->paginate(10);

        

        return view('admin.orders.list', compact('page_heading', 'list', 'order_id', 'name', 'from', 'to'));
    }
    public function commission(Request $request)
    {
        $page_heading = "Commission Report";
        $order_id = $_GET['order_id'] ?? '';
        $name = $_GET['name'] ?? '';
        $from = !empty($_GET['from']) ? date('Y-m-d', strtotime($_GET['from'])) : '';
        $to = !empty($_GET['to']) ? date('Y-m-d', strtotime($_GET['to'])) : '';
        $inf_user = $_GET['inf_user'] ?? '';
        $moda_user = $_GET['moda_user'] ?? '';

        $where['order_status'] = config('global.order_status_delivered');
        $where['is_returned'] = 0;
        $list = OrderProductsModel::select('product_name','orders.created_at as ord_created_at', 'orders.grand_total as ord_grand_total', 'orders.total as ord_total', 'orders.discount as ord_discount', 'order_products.*', 'u1.name', 'u2.name as moda_user', 'u3.name as influencer','store_name')->join('product', 'product.id', 'order_products.product_id')->join('stores', 'stores.id', 'product.store_id')->join('orders', 'orders.order_id', 'order_products.order_id')->leftjoin('users as u1', 'u1.id', 'orders.user_id')->leftjoin('users as u2', 'u2.id', 'order_products.influencer_id')->leftjoin('users as u3', 'u3.id', 'order_products.influencer_user_id')->where($where)->whereDate('show_commission_on', '<=', gmdate('Y-m-d'))->orderBy('show_commission_on','desc');
        if ($order_id) {
            $list = $list->where('orders.order_id', $order_id);
        }
        if ($inf_user) {
            $list = $list->where('influencer_user_id', $inf_user);
        }
        if ($moda_user) {
            $list = $list->where('influencer_id', $moda_user);
        }
        if ($from) {
            $list = $list->whereDate('show_commission_on', '>=', $from);
        }
        if ($to) {
            $list = $list->where('show_commission_on', '<=', $to);
        }
        if ($request->submit != "export") {
            $list = $list->paginate(10);
        } else {
            $list = $list->paginate(9999999999999999);
        }
        foreach ($list as $key => $val) {
            $product_image = '';
            if ($val->product_attribute_id) {
                $det = DB::table('product_selected_attribute_list')->select('image')->where('product_id', $val->product_id)->where('product_attribute_id', $val->product_attribute_id)->first();
                if ($det) {
                    $images = $det->image;
                    $images = explode(",", $det->image);
                    $images = array_values(array_filter($images));
                    $product_image = (count($images) > 0) ? $images[0] : $det->image;
                }
            } else {
                $det = DB::table('product_selected_attribute_list')->select('image')->where('product_id', $val->product_id)->orderBy('product_attribute_id', 'DESC')->limit(1)->first();
                if ($det) {
                    $images = $det->image;
                    $images = explode(",", $det->image);
                    $images = array_values(array_filter($images));
                    $product_image = (count($images) > 0) ? $images[0] : $det->image;
                }
            }
            $list[$key]->prod_image = $product_image ? url(config('global.upload_path') . '/' . config('global.product_image_upload_dir') . $product_image) : '';
        }

        $moda_users = OrderProductsModel::select('u2.name as moda_user','influencer_id')->join('users as u2', 'u2.id', 'order_products.influencer_id')->where($where)->whereDate('show_commission_on', '<=', gmdate('Y-m-d'))->groupby('influencer_id','u2.name')->get();


        $inf_users = OrderProductsModel::select('u3.name as inf_user','influencer_user_id')->join('users as u3', 'u3.id', 'order_products.influencer_user_id')->where($where)->whereDate('show_commission_on', '<=', gmdate('Y-m-d'))->groupby('influencer_user_id','u3.name')->get();
        

        if ($request->submit == "export") {
            //export

            $rows = array();
            $i = 1;
            foreach ($list as $key => $item) {
                $rows[$key]['i'] = $i;
                $rows[$key]['order_id'] = config('global.sale_order_prefix').date(date('Ymd', strtotime($item->ord_created_at))).$item->order_id;;
                $rows[$key]['total'] = $item->total;
                $rows[$key]['discount'] = $item->discount;
                $rows[$key]['grand_total'] = $item->grand_total;
                $rows[$key]['name'] = $item->name;
                $rows[$key]['influencer'] = $item->influencer;
                $rows[$key]['moda_user'] = $item->moda_user;
                $rows[$key]['admin_commission'] = $item->admin_commission.'('.$item->admin_commission_percentage.'%)';
                $rows[$key]['moda_commission_percentage'] = $item->moda_commission ? $item->moda_commission.'('.$item->moda_commission_percentage.'%)' : '-';
                $rows[$key]['influencer_commission'] = $item->influencer_commission ? $item->influencer_commission.'('.$item->influencer_commission_percentage.'%)' : '-';
                $rows[$key]['date'] = get_date_in_timezone($val->show_commission_on, 'd-M-Y');
                $i++;
            }
            $headings = [
                "#",
                "Order ID",
                "Total",
                "Discount",
                "Grand Total",
                "User",
                "Influencer",
                "Moda User",
                "Admin Commission",
                "Moda Commission",
                "Influencer Commission",
                "Date",
               
            ];
            $coll = new ExportReports([$rows], $headings);
            $ex = Excel::download($coll, 'commission_report_' . date('d_m_Y_h_i_s') . '.xlsx');
            if (ob_get_length()) {
                ob_end_clean();
            }

            return $ex;
            //export end
        } else {
            return view('admin.orders.commission', compact('page_heading', 'list', 'order_id', 'name', 'from', 'to','moda_users','inf_users','inf_user','moda_user'));
        }
    }

    public function details(Request $request, $id)
    {
        if (!check_permission('orders', 'Details')) {
            abort(404);
        }
        $page_heading = "Orders Details";
        //$list =  OrderProductsModel::select('orders.*',DB::raw("CONCAT(res_users.first_name,' ',res_users.last_name) as customer_name"))->->leftjoin('res_users','res_users.id','orders.user_id')->with('vendor')->where(['order_id'=>$id])->paginate(10);
        //if($list->total()){
        //foreach($list->items() as $key=>$row){

        //$list->items()[$key]->tickets=OrderModel::tickets($row->id);
        //$list->items()[$key]->product_name=OrderProductsModel::product_name($row->product_id,$row->product_type);
        //}
        // }
        $filter['order_id'] = $id;

        $page = (int) $request->page ?? 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $list = OrderProductsModel::get_order_details($filter)->get();
        $list = process_order($list);

        $highest_order_prd_status = OrderProductsModel::where('order_id', $id)->orderby('order_status', 'desc')->first();
        $show_cancel = 0;
        if (isset($highest_order_prd_status->order_status) && $highest_order_prd_status->order_status == 1) {
            $show_cancel = 1;
        }
// dd($list);
        return view('admin.orders.details', compact('page_heading', 'list', 'show_cancel'));
    }

    public function edit_order(Request $request, $id)
    {
        $page_heading = "Orders Details Edit";
        //$list =  OrderProductsModel::select('orders.*',DB::raw("CONCAT(res_users.first_name,' ',res_users.last_name) as customer_name"))->->leftjoin('res_users','res_users.id','orders.user_id')->with('vendor')->where(['order_id'=>$id])->paginate(10);
        //if($list->total()){
        //foreach($list->items() as $key=>$row){

        //$list->items()[$key]->tickets=OrderModel::tickets($row->id);
        //$list->items()[$key]->product_name=OrderProductsModel::product_name($row->product_id,$row->product_type);
        //}
        // }
        $filter['order_id'] = $id;

        $page = (int) $request->page ?? 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $list = OrderProductsModel::get_order_details($filter)->skip($offset)->take($limit)->get();
        $list = process_order($list);

        return view('admin.orders.details_edit', compact('page_heading', 'list'));
    }

    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
        if ($request->detailsid && $request->statusid) {
            $update['status'] = $request->statusid;
            if (OrderModel::where('order_id', $request->detailsid)->update($update)) {

                // $det = OrderProductsModel::find($request->detailsid);
                // if ($request->statusid == config('global.order_status_delivered')) {
                //     $prd_det = \App\Models\ProductModel::find($det->product_id);
                //     $show_commission_on = gmdate("Y-m-d");
                //     if ($prd_det->ret_applicable) {
                //         $ret_policy_days = $prd_det->ret_policy_days ?? 0;
                //         if ($ret_policy_days) {
                //             $show_commission_on = \Carbon\Carbon::now()->addDays($ret_policy_days + 1);
                //         }
                //     }
                //     OrderProductsModel::where('id', $request->detailsid)->update(['show_commission_on' => $show_commission_on]);
                // }

                $ord = OrderModel::with('customer')->where('order_id', $request->detailsid)->first();
                if($request->statusid==config('global.order_status_cancelled')){
                    
                    $amount_to_credit = $ord->grand_total;
                    $w_data = [
                        'user_id' => $ord->customer->id,
                        'wallet_amount' => $amount_to_credit,
                        'pay_type' => 'credited',
                        'description' => 'Order Cancelled',
                    ];
                    if (wallet_history($w_data)) {
                        $users = \App\Models\User::find($ord->customer->id);
                        $users->wallet_amount = $users->wallet_amount + $amount_to_credit;
                        $users->save();
                    }
                }
                
                $title = "#".config('global.sale_order_prefix').date(date('Ymd', strtotime($ord->created_at))).$request->detailsid;
                $ord_st = order_status($request->statusid);
                $description = "Your order status updated to " . $ord_st;
                $notification_id = time();
                $ntype = 'order_status_changed';
                if (!empty($ord->customer->firebase_user_key)) {
                    $notification_data["Nottifications/" . $ord->customer->firebase_user_key . "/" . $notification_id] = [
                        "title" => $title,
                        "description" => $description,
                        "notificationType" => $ntype,
                        "createdAt" => gmdate("d-m-Y H:i:s", $notification_id),
                        "orderId" => (string) $request->detailsid,
                        "url" => "",
                        "imageURL" => '',
                        "read" => "0",
                        "seen" => "0",
                    ];
                    $this->database->getReference()->update($notification_data);
                }

                if (!empty($ord->customer->user_device_token)) {
                    send_single_notification($ord->customer->user_device_token, [
                        "title" => $title,
                        "body" => $description,
                        "icon" => 'myicon',
                        "sound" => 'default',
                        "click_action" => "EcomNotification"],
                        ["type" => $ntype,
                            "notificationID" => $notification_id,
                            "orderId" => (string) $request->detailsid,
                            "imageURL" => "",
                        ]);
                }
                $name = $ord->customer->name ?? $ord->customer->first_name . ' ' . $ord->customer->last_name;
                
                exec("php " . base_path() . "/artisan send:send_order_status_change_email --uri=" . urlencode($ord->customer->email) . " --uri2=" . $request->detailsid . " --uri3=" . urlencode($name) . " --uri4=" . $ord->customer->id . " --uri5=" . urlencode($ord_st) . " > /dev/null 2>&1 & ");

                $status = "1";
                $message = "Successfully updated";
            } else {
                $message = "Something went wrong";
            }
        } else {
            $message = "Something went wrong";
        }
        echo json_encode(['status' => $status, 'message' => $message]);
    }

    public function cancel_order(Request $request)
    {
        $status = "0";
        $message = "";

        $order = OrderModel::with(['products'])->where('order_id', $request->order_id)->first();

        if ($order) {
            $highest_order_prd_status = OrderProductsModel::where('order_id', $request->order_id)->orderby('order_status', 'desc')->first();

            if (isset($highest_order_prd_status->order_status) && $highest_order_prd_status->order_status == 1) {
                $amount_to_credit = $order->grand_total;
                $w_data = [
                    'user_id' => $order->user_id,
                    'wallet_amount' => $amount_to_credit,
                    'pay_type' => 'credited',
                    'description' => 'Order Cancelled',
                ];
                if (wallet_history($w_data)) {
                    $users = \App\Models\User::find($order->user_id);
                    $users->wallet_amount = $users->wallet_amount + $amount_to_credit;
                    $users->save();
                    $c_st = config('global.order_status_cancelled');
                    OrderModel::where('order_id', $request->order_id)->update(['status' => $c_st]);
                    OrderProductsModel::where('order_id', $request->order_id)->update(['order_status' => $c_st]);
                    $status = "1";
                    $message = "Order has been cancelled successfully. Amount has refunded to user wallet.";
                    $title = 'Order Cancelled';
                    $description = 'Your order has been cancelled successfully. Amount has refunded to your wallet.';
                    $notification_id = time();
                    $ntype = 'order_cancelled';
                    if (!empty($users->firebase_user_key)) {
                        $notification_data["Nottifications/" . $users->firebase_user_key . "/" . $notification_id] = [
                            "title" => $title,
                            "description" => $description,
                            "notificationType" => $ntype,
                            "createdAt" => gmdate("d-m-Y H:i:s", $notification_id),
                            "orderId" => (string) $request->order_id,
                            "url" => "",
                            "imageURL" => '',
                            "read" => "0",
                            "seen" => "0",
                        ];
                        $this->database->getReference()->update($notification_data);
                    }

                    if (!empty($users->user_device_token)) {
                        send_single_notification($users->user_device_token, [
                            "title" => $title,
                            "body" => $description,
                            "icon" => 'myicon',
                            "sound" => 'default',
                            "click_action" => "EcomNotification"],
                            ["type" => $ntype,
                                "notificationID" => $notification_id,
                                "orderId" => (string) $request->order_id,
                                "imageURL" => "",
                            ]);
                    }

                } else {
                    $status = "0";
                    $message = "Something went wrong!! Try again";
                }
            } else {
                $status = "0";
                $message = "You can't cancel this order";
            }

        } else {
            $message = "Something went wrong";
        }

        echo json_encode(['status' => $status, 'message' => $message]);
    }

}
