<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\OrderModel;
use App\Models\OrderProductsModel;
use App\Models\User;
use App\Models\UserAdress;
use App\Models\OrderStatusHistroy;
use App\Models\PaymentReport;
use App\Models\VendorDetailsModel;
use DB;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Validator;

class OrderController extends Controller
{
    public function __construct(Database $database)
    {
        $this->database = $database;
    }
    private function validateAccesToken($access_token)
    {

        $user = User::where(['user_access_token' => $access_token])->get();

        if ($user->count() == 0) {
            http_response_code(401);
            echo json_encode([
                'status' => "0",
                'message' => 'Invalid login',
                'oData' => [],
                'errors' => (object) [],
            ]);
            exit;

        } else {
            $user = $user->first();
            if ($user != null) { //$user->active == 1
                return $user->id;
            } else {
                http_response_code(401);
                echo json_encode([
                    'status' => "0",
                    'message' => 'Invalid login',
                    'oData' => [],
                    'errors' => (object) [],
                ]);
                exit;
                return response()->json([
                    'status' => "0",
                    'message' => 'Invalid login',
                    'oData' => [],
                    'errors' => (object) [],
                ], 401);
                exit;
            }
        }
    }

    public function my_orders(REQUEST $request)
    {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];

        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {

            $user_id = $this->validateAccesToken($request->access_token);
            $limit = $request->limit??20;
            $page = $request->page??1;
            $offset = ($page-1) * $limit;
            $order_list = OrderModel::with(['products' => function ($qr) {
                $qr->select('order_products.id', 'order_id', 'product_id', 'default_attribute_id','order_products.product_attribute_id')->join('product', 'product.id', 'order_products.product_id');
            }])->where('user_id', $user_id)->orderBy('orders.order_id', 'desc')->skip($offset)->take($limit)->get();
            
            foreach ($order_list as $key => $val) {
                //$order_list[$key]->invoice_id = config('global.sale_order_prefix') . date(date('Ymd', strtotime($val->created_at))) . $val->order_id;
                $order_list[$key]->ord_no = '#'.config('global.sale_order_prefix').date(date('Ymd', strtotime($val->created_at))).$val->order_id;
                $order_list[$key]->status_text = order_status($val->status);
                if($request->timezone){
                    $order_list[$key]->booking_date = api_date_in_timezone($val->created_at,'Y-m-d H:i:s',$request->timezone);
                }
                $order_list[$key]->delivery_charge =  $val->shipping_charge;
                // $order_list[$key]->grand_total = $val->shipping_charge + $val->grand_total;
                $order_list[$key]->grand_total = $val->grand_total;
                // $order_list[$key]->delivery_charge =  $val->shipping_charge;
                $products = $val->products;
                foreach ($products as $pkey => $pval) {
                    $product_image = '';
                    if ($pval->product_attribute_id) {
                        $det = DB::table('product_selected_attribute_list')->select('image')->where('product_id', $pval->product_id)->where('product_attribute_id', $pval->product_attribute_id)->first();
                        if ($det) {
                            // $images = $det->image;
                            // $images = explode(",", $det->image);
                            // $images = array_values(array_filter($images));
                            // $product_image = (count($images) > 0) ? $images[0] : $det->image;
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
                                $products[$pkey]->images = $prd_img;
                            } else {
                                $products[$pkey]->images = [];
                            }



                        }
                    } else {
                        $det = DB::table('product_selected_attribute_list')->select('image')->where('product_id', $pval->product_id)->orderBy('product_attribute_id', 'DESC')->limit(1)->first();
                        if ($det) {
                            // $images = $det->image;
                            // $images = explode(",", $det->image);
                            // $images = array_values(array_filter($images));
                            // $product_image = (count($images) > 0) ? $images[0] : $det->image;
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
                                $products[$pkey]->images = $prd_img;
                            } else {
                                $products[$pkey]->images = [];
                            }
                        }
                    }
                    // $products[$pkey]->image = $product_image ? url(config('global.upload_path') . '/' . config('global.product_image_upload_dir') . $product_image) : '';
                }
                $order_list[$key]->products = $products;
            }
            
            $o_data['list'] = convert_all_elements_to_string($order_list);
            $o_data['currency_code'] = config('global.default_currency_code');
            $o_data['pagination']['limit'] =(string)$limit;
            $o_data['pagination']['page'] =(string)$page;
        }
        return response()->json(['status' => $status, 'errors' => (object)$errors, 'message' => $message, 'oData' => (object)$o_data], 200);
    }
    public function my_order_details(REQUEST $request)
    {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];

        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {

            $user_id = $this->validateAccesToken($request->access_token);
            $order = OrderModel::with(['products' => function ($qr) {
                $qr->select('order_products.*', 'product_attribute_id as product_variant_id', 'default_attribute_id', 'product_name', 'ret_applicable', 'ret_policy_days', 'ret_policy')->join('product', 'product.id', 'order_products.product_id');
            }])->where('order_id', $request->order_id)->first();

            if ($order) {
                //$order->invoice_id = config('global.sale_order_prefix') . date(date('Ymd', strtotime($order->created_at))) . $order->order_id;

                $order->ord_no = '#'.config('global.sale_order_prefix').date(date('Ymd', strtotime($order->created_at))).$order->order_id;
                $pay_type ="Card";
                if($order->payment_mode==3){
                    $pay_type ="Apple Pay";
                }
                if($order->payment_mode==4){
                    $pay_type ="Google Pay";
                }
                $order->pay_type = $pay_type;
                $order->status_text = order_status($order->status);
                // $order->show_pay_button = "0";
                // if ($order->status ==  config('global.order_status_accepted')) {
                //     $order->show_pay_button = "1";
                // }
                // $order->show_cancel = "0";
                // if ($order->status ==  config('global.order_status_pending')) {
                //     $order->show_cancel = "1";
                // }
                if($request->timezone){
                    $order->booking_date = api_date_in_timezone($order->created_at,'Y-m-d H:i:s',$request->timezone);
                }
                $order->delivery_charge = $order->shipping_charge;
                // $order->grand_total = $order->shipping_charge + $order->grand_total;
                $order->grand_total =  $order->grand_total;
                $address_user = \App\Models\UserAdress::get_address_details($order->address_id);
                if(!$address_user)
                {
                $address_user =  (object) null;
                }
                else
                {
                 $address_user = convert_all_elements_to_string($address_user->toArray());   
                }
                $order->address =  $address_user;
                $products = $order->products;
                $today = gmdate("Y-m-d");
                
                $vendordatils = VendorDetailsModel::select('deliverydays')->where('user_id',$products[0]->vendor_id??0)->first();
                $expected_delivery = 3;
                if(!empty($vendordatils))
                {
                $expected_delivery = strtotime("+ $vendordatils->deliverydays day");    
                }
                
                $order->expected_delivery = date("Y-m-d", $expected_delivery);
                foreach ($products as $pkey => $pval) {
                    // $ret_applicable = $pval->ret_applicable;
                    // $ret_policy_days = $pval->ret_policy_days;
                    // unset($products[$pkey]->ret_applicable);
                    // unset($products[$pkey]->ret_policy_days);
                    // $show_return = 0;
                    // if ($pval->order_status == config('global.order_status_delivered') && $ret_applicable && $pval->delivered_on && !$pval->is_returned) {
                    //     $datetime1 = new \DateTime($today);
                    //     $datetime2 = new \DateTime($pval->delivered_on);
                    //     $interval = $datetime1->diff($datetime2);
                    //     $days = $interval->format('%a');
                    //     if (abs($days) <= $ret_policy_days) {
                    //         $show_return = 1;
                    //     }
                    // }
                    // $products[$pkey]->order_status_text = order_status($pval->order_status);
                    // if ($pval->is_returned) {
                    //     $order_status_text = 'Return Pending';
                    //     if ($pval->ret_status == 1) {
                    //         $order_status_text = 'Returned';
                    //     }
                    //     if ($pval->ret_status == 2) {
                    //         $order_status_text = 'Return Rejected';
                    //     }
                    //     $products[$pkey]->order_status_text = $order_status_text;
                    // }
                    // $products[$pkey]->is_return_applicable = $show_return;
                    $product_image = '';
                    if ($pval->product_attribute_id	) {
                        $det = DB::table('product_selected_attribute_list')->select('image')->where('product_id', $pval->product_id)->where('product_attribute_id', $pval->product_attribute_id	)->first();
                        if ($det) {
                            $images = $det->image;
                            $images = explode(",", $det->image);
                            $images = array_values(array_filter($images));
                            $product_image = (count($images) > 0) ? $images[0] : $det->image;
                        }
                    } else {
                        $det = DB::table('product_selected_attribute_list')->select('image')->where('product_id', $pval->product_id)->orderBy('product_attribute_id', 'DESC')->limit(1)->first();
                        if ($det) {
                            $images = $det->image;
                            $images = explode(",", $det->image);
                            $images = array_values(array_filter($images));
                            $product_image = (count($images) > 0) ? $images[0] : $det->image;
                        }
                    }
                    $products[$pkey]->image = $product_image ? url(config('global.upload_path') . '/' . config('global.product_image_upload_dir') . $product_image) : '';
                    $product_avg_rating   = \App\Models\Rating::avg_rating(['type'=>1,'product_id'=>$pval->product_id,'user_id'=>$user_id]);
                    $rating  = \App\Models\Rating::where(['type'=>1,'product_id'=>$pval->product_id,'user_id'=>$user_id])->first();
                    $products[$pkey]->is_rated = 0;
                    $products[$pkey]->rating = 0;

                    if($product_avg_rating)
                    {
                     $products[$pkey]->is_rated = 1;  
                     $products[$pkey]->rating = $rating->rating;
                    }


                }
                $order->products = $products;
                $order->currency_code = config('global.default_currency_code');
            } 
            $o_data = $order ? convert_all_elements_to_string($order->toArray()) : [];
            $o_data['address'] = (object) $address_user;
        }
        return response()->json(['status' => $status, 'errors' => (object)$errors, 'message' => $message, 'oData' => (object)$o_data], 200);
    }

   
    public function cancel_order(REQUEST $request)
    {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];

        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {

            $user_id = $this->validateAccesToken($request->access_token);
            $order = OrderModel::with(['products'])->where('user_id', $user_id)->where('order_id', $request->order_id)->first();

            if ($order) {
                
                if ($order->status == config("global.order_status_pending")) {
                    $amount_to_credit = $order->grand_total;
                    $w_data = [
                        'user_id' => $user_id,
                        'wallet_amount' => $amount_to_credit,
                        'pay_type' => 'credited',
                        'description' => 'Order Cancelled',
                    ];
                    if (wallet_history($w_data)) {
                        $users = User::find($user_id);
                        $users->wallet_amount = $users->wallet_amount + $amount_to_credit;
                        $users->save();
                        $c_st = config('global.order_status_cancelled');
                        OrderModel::where('order_id', $request->order_id)->update(['status' => $c_st]);
                        OrderProductsModel::where('order_id', $request->order_id)->update(['order_status' => $c_st]);
                        $status = "1";
                        $message = "Your order has been cancelled successfully. Amount has refunded to your wallet.";

                        $order_history =  new OrderStatusHistroy();
                        $order_history->order_id  = $request->order_id;
                        $order_history->order_status = $c_st;
                        $order_history->created_at = gmdate('Y-m-d H:i:s');
                        $order_history->updated_at = gmdate('Y-m-d H:i:s');
                        $order_history->save();
                        if( config('global.server_mode') == 'local'){
                            \Artisan::call('send_status_nottification:order '.$request->order_id);
                        }else{
                            exec("php ".base_path()."/artisan send_status_nottification:order ".$request->order_id." > /dev/null 2>&1 & ");  
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
                $status = "0";
                $message = "No order found";
            }
        }
        return response()->json(['status' => $status, 'errors' => (object)$errors, 'message' => $message, 'oData' => (object)$o_data], 200);
    }

    public function return_order_item(REQUEST $request)
    {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];

        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
            'id' => 'required',
            'reason' => 'required',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {

            $user_id = $this->validateAccesToken($request->access_token);
            $order = OrderProductsModel::select('order_products.*', 'ret_applicable', 'ret_policy_days', 'ret_policy')->join('product', 'product.id', 'order_products.product_id')->where('order_products.id', $request->id)->first();

            if ($order) {
                $today = gmdate("Y-m-d");
                $is_return_applicable = 0;
                if ($order->order_status == config('global.order_status_delivered') && $order->ret_applicable && $order->delivered_on && !$order->is_returned) {
                    $datetime1 = new \DateTime($today);
                    $datetime2 = new \DateTime($order->delivered_on);
                    $interval = $datetime1->diff($datetime2);
                    $days = $interval->format('%a');
                    if (abs($days) <= $order->ret_policy_days) {
                        $is_return_applicable = 1;
                    }
                }
                if ($is_return_applicable) {
                    $update['is_returned'] = 1;
                    $update['returned_on'] = $today;
                    $update['ret_reason'] = $request->reason;
                    if (OrderProductsModel::where('id', $request->id)->update($update)) {
                        $status = "1";
                        $message = "Your return request successfully submitted. We'll notify you the status";
                        $users = User::find($user_id);
                        $title = 'Return Submitted';
                        $description = $message;
                        $notification_id = time();
                        $ntype = 'return_submitted';
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
                    $message = "You can't return this item";
                }
            }else{
                $status = "0";
                $message = "No details found";
            }
        }
        return response()->json(['status' => $status, 'errors' => $errors, 'message' => $message, 'oData' => $o_data], 200);
    }

}
