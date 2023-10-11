<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CouponCategory;
use App\Models\CouponHistory;
use App\Models\Coupons;
use App\Models\OrderModel;
use App\Models\OrderProductsModel;
use App\Models\ProductModel;
use App\Models\SettingsModel;
use App\Models\User;
use App\Models\UserAdress;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Validator;
use Unicodeveloper\Paystack\Facades\Paystack;

class CartController extends Controller
{
    public $lang = '';
    public function __construct(Database $database,Request $request)
    {
        $this->database = $database;
        if(isset($request->lang)) {
            \App::setLocale($request->lang);
        }
        $this->lang = \App::getLocale();
    }
    private function getUserId($access_token){
        $user_id = 0;
        $user = User::where(['user_access_token' => $access_token])->where('user_access_token','!=','')->get();
        if($user->count() > 0){
            $user_id = $user->first()->id;
        }
        return $user_id;
    }
    private function validateAccesToken($access_token)
    {

        $user = User::where(['user_access_token' => $access_token])->get();

        if ($user->count() == 0) {
            http_response_code(401);
            echo json_encode([
                'status' => "0",
                'message' => trans('validation.invalid_login'),
                'oData' => [],
                'errors' => (object) [],
            ]);
            exit;

        } else {
            $user = $user->first();
            if ($user->active == 1) {
                return $user->id;
            } else {
                http_response_code(401);
                echo json_encode([
                    'status' => "0",
                    'message' => trans('validation.invalid_login'),
                    'oData' => [],
                    'errors' => (object) [],
                ]);
                exit;
                return response()->json([
                    'status' => "0",
                    'message' => trans('validation.invalid_login'),
                    'oData' => [],
                    'errors' => (object) [],
                ], 401);
                exit;
            }
        }
    }
    public function add_to_cart(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $rules = [
            // 'access_token' => 'required',
            'product_id' => 'required|numeric|min:0|not_in:0',
            'product_variant_id' => 'required|numeric|min:0|not_in:0',
            // 'quantity' => 'required|numeric|min:0|not_in:0',
            // 'device_cart_id'=>'required',
        ];
        $messages = [
            'access_token.required' => trans('validation.access_token_required'),
            'product_id.required' => trans('validation.product_id_required'),
            'product_variant_id.required'=>trans('validation.product_variant_id_required'),
            // 'quantity.required' => 'Quantity is required',
            // 'device_cart_id.required' => Device 'Cart ID is required',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);

        if ($validator->fails()) {
            $status = "0";
            $message = trans('validation.validation_error_occured');
            $errors = $validator->messages();
        } else {
            // $user_id = $this->validateAccesToken($request->access_token);
            $user_id = $this->getUserId($request->access_token);
            $product_id = $request->product_id;
            $store_id = 0;
            $product_variant_id = $request->product_variant_id;
            

            list($product_status, $product_data, $message) = ProductModel::getProductVariant($product_id, $product_variant_id);
            $quantity = isset($request->quantity) ? $request->quantity : 1;
            if ($product_status && !empty($product_data)) {

                $cart_key = '';

                $i_data['user_id'] = $user_id;
                $i_data['product_id'] = $product_id;
                $i_data['product_attribute_id'] = (int) $product_variant_id;
                $i_data['quantity'] = $quantity;
                $i_data['store_id'] = $store_id;
                $i_data['device_cart_id'] = $request->device_cart_id;
                $i_data['created_at'] = gmdate("Y-m-d H:i:s");

                $in_stock = $product_data->stock_quantity;

                $cart_condition = [];
                $cart_condition = [
                    "product_id" => $i_data['product_id'],
                    "product_attribute_id" => $i_data['product_attribute_id'],
                    // "user_id" => $user_id,
                ];
                if($user_id > 0){
                    $cart_condition['user_id'] = $user_id;
                }else{
                    $cart_condition['device_cart_id'] = $request->device_cart_id;
                }

                $product_cart_data = Cart::get_user_cart($cart_condition);
                if (count($product_cart_data)) {
                    $status = "";

                    if (count($product_cart_data) == 1) {
                        $product_cart_data = $product_cart_data[0];
                        // dd($quantity + $product_cart_data->quantity , $in_stock,$product_cart_data );
                        if ((($quantity + $product_cart_data->quantity) <= $in_stock) || ($product_data->allow_back_order == 1)) {
                        // if (($quantity <= $in_stock) || ($product_data->allow_back_order == 1)) {
                            $up['quantity'] = $quantity + $product_cart_data->quantity;
                            Cart::update_cart($up, ["id" => $product_cart_data->id]);

                            $status = "1";
                            $message = trans('validation.product_added_to_your_cart');

                        } else {
                            $status = "3";
                            $message = trans('validation.unable_to_increase_the_product_quantity_beacuse_you_reached_maximum_level_of_stock');
                        }
                    } else {
                        $status = "3";
                        $message = trans('validation.this_item_has_multiple_customizations_added_increase_the_correct_item_from_the_cart');
                    }

                } else {
                    if (($quantity <= $in_stock) || ($product_data->allow_back_order == 1)) {
                        Cart::create_cart($i_data);
                        $status = "1";
                        $message = trans('validation.product_added_to_your_cart');
                    } else {
                        $status = "3";
                        $message = trans('validation.unable_to_add_product_to_cart_due_to_limited_stock');
                    }
                }

                $o_data = $this->process_cart($user_id,$request->device_cart_id);
                $o_data['device_cart_id'] = $request->device_cart_id;
                $o_data['cart_count'] = (string)(count($o_data['cart_items']) ?? 0);
                $status = $status ?? "1";

            } else {
                $status = "3";
                $message = trans('validation.no_product_exists');
            }

        }
        return response()->json(['status' => $status, 'errors' => $errors, 'message' => $message, 'oData' => $o_data], 200);
    }
    public function get_cart(Request $request)
    {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];
        // $validator = Validator::make($request->all(), [
        //     'access_token' => 'required',
        // ]);
        $rules = [
            // 'access_token' => 'required',
        ];
        $messages = [
            'access_token.required' => trans('validation.access_token_required'),
        ];
        $validator = Validator::make($request->all(),$rules,$messages);

        if ($validator->fails()) {
            $status = "0";
            $message = trans('validation.validation_error_occured');
            $errors = $validator->messages();
        } else {
            // $user_id = $this->validateAccesToken($request->access_token);
            $user_id = $request->user_id ? $request->user_id :  $this->getUserId($request->access_token);
            $address =  '';
            if(isset($request->address_id)) {
                $address =  UserAdress::get_address_details($request->address_id);

            }
            $o_data = $this->process_cart($user_id,$request->device_cart_id,$address);
        }

        return response()->json(['status' => $status, 'message' => $message, 'errors' => $errors, 'oData' => $o_data]);
    }
    public function checkout(Request $request)
    {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];
        // $validator = Validator::make($request->all(), [
        //     'access_token' => 'required',
        // ]);
        $rules = [
            'access_token' => 'required',
        ];
        $messages = [
            'access_token.required' => trans('validation.access_token_required'),
        ];
        $validator = Validator::make($request->all(),$rules,$messages);

        if ($validator->fails()) {
            $status = "0";
            $message = trans('validation.validation_error_occured');
            $errors = $validator->messages();
        } else {
            $user_id = $this->validateAccesToken($request->access_token);

            if($request->device_cart_id){
                Cart::where('device_cart_id',$request->device_cart_id)->update(['user_id'=>$user_id]);
            }
            
            $address = UserAdress::get_user_default_address($user_id); 
            if(isset($request->address_id)) {
                $address =  UserAdress::get_address_details($request->address_id);

            }
            $o_data = $this->process_cart($user_id,$request->device_cart_id,$address);
            $de_address = new \stdClass;
            if($address)
            {
             $de_address = $address->toArray();   
            }
            $o_data['default_address'] = convert_all_elements_to_string($de_address);
            $useraddress = UserAdress::get_address_list($user_id);
            if(count($useraddress))
            {
             $useraddress = $useraddress->toArray();  
            }
            $o_data['address_list'] = convert_all_elements_to_string($useraddress);
        }

        return response()->json(['status' => $status, 'message' => $message, 'errors' => $errors, 'oData' =>$o_data]);
    }
    public function update_cart(Request $request)
    {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];
        // $validator = Validator::make($request->all(), [
        //     'access_token' => 'required',
        //     'cart_id' => 'required',
        //     'type' => 'required',
        // ]);
        $rules = [
            // 'access_token' => 'required',
            'cart_id' => 'required',
            'type' => 'required',
        ];
        $messages = [
            'access_token.required' => trans('validation.access_token_required'),
            'cart_id.required' => trans('validation.cart_id_required'),
            'type.required' => trans('validation.type_required'),
        ];
        $validator = Validator::make($request->all(),$rules,$messages);

        if ($validator->fails()) {
            $status = "0";
            $message = trans('validation.validation_error_occured');
            $errors = $validator->messages();
        } else {
            // $user_id = $this->validateAccesToken($request->access_token);
            $user_id = $request->user_id ? $request->user_id :  $this->getUserId($request->access_token);

            $cart_condition = [
                "id" => $request->cart_id,
                // "user_id" => $user_id,
            ];
            if($user_id > 0){
                $cart_condition['user_id'] = $user_id;
            }else{
                $cart_condition['device_cart_id'] = $request->device_cart_id;
            }
            if ($request->type == "add") {
                $add = true;
                $product_cart_data = Cart::get_user_cart($cart_condition);
                if (count($product_cart_data) == 1) {
                    $product_cart_data = $product_cart_data[0];
                    list($product_status, $product_data, $message) = ProductModel::getProductVariant($product_cart_data->product_id, $product_cart_data->product_variant_id);
                    // dd($product_data,($product_cart_data->quantity > $product_data->stock_quantity));
                    if(($product_cart_data->quantity >= $product_data->stock_quantity) && ($product_data->allow_back_order == 0)){
                        $add = false;
                    }
                }
                if ($add) {
                    Cart::where($cart_condition)->increment('quantity', 1);
                    $message = trans('validation.cart_updated');
                } else {
                    $message = trans('validation.invalid_data_passed');
                }
            } else {

                if (Cart::where($cart_condition)->first()->quantity > 1) {
                    Cart::where($cart_condition)->decrement('quantity', 1);
                    $message = trans('validation.cart_updated');
                } else {
                    $message = trans('validation.invalid_data_passed');
                }
            }

            $o_data = $this->process_cart($user_id,$request->device_cart_id);
        }

        return response()->json(['status' => $status, 'message' => $message, 'errors' => $errors, 'oData' => $o_data]);
    }
    public function process_cart($user_id,$device_cart_id = '',$address ='')
    {
        if($user_id){
            $where['cart.user_id'] = $user_id;
        }else{
            $where['cart.device_cart_id'] = $device_cart_id;
        }
        
        $where['product.deleted'] = 0;
        $where['product.product_status'] = 1;
       

        $cart = Cart::select('cart.*')->where($where)->join('product', 'product.id', 'cart.product_id')->orderby('cart.created_at','desc')->get();
        $product = [];

        $cart_total = 0;
        foreach ($cart as $key => $val) {
            $device_cart_id = $val->device_cart_id ? $val->device_cart_id : "";
            $cart[$key]->device_cart_id = (string) $device_cart_id;
            list($status, $product, $message) = ProductModel::getProductVariant($val->product_id, $val->product_attribute_id);
            if ($status && !empty($product)) {
                $product = process_product_data_api($product);
                $amt = $product['regular_price'];
                if ($product['sale_price']) {
                    $amt = $product['sale_price'];
                }
                $product_total_amount = $amt * $val->quantity;
                $product['total_amount'] = $product_total_amount;
                $cart_total += $product_total_amount;
            }
            $cart[$key]->product_details = $product;
        }
        $settings = SettingsModel::first();
        $tax_percentage = 0;
        if (isset($settings->tax_percentage)) {
            $tax_percentage = $settings->tax_percentage;
        }

        $shipping_charge = 0;
        if (isset($settings->shipping_charge)) {
            //$shipping_charge = $settings->shipping_charge;
        }
        if(isset($address) && $address !=''){
            $shipping_charge = $address->delivery_charge??0;
        }
        $tax_amount = ($cart_total * $tax_percentage) / 100;
        $delivery_charge = 0;;
        $grand_total = $tax_amount + $cart_total;       
        $cart_count = count($cart);
        if($cart_count){
            $delivery_charge = $shipping_charge;
            $grand_total = $grand_total+$delivery_charge;
        }
        $cart_items = $cart;
        $cart_total = round($cart_total, 2);
        $grand_total = round($grand_total, 2);
        $tax_amount = round($tax_amount, 2);
        return ['cart_total' => (string)$cart_total,'sub_total' => (string)$cart_total, 'tax_amount' => (string)$tax_amount, 'discount' => "0",'delivery_charge'=>(string)$delivery_charge, 'grand_total' => (string)$grand_total, 'cart_count' => (string)$cart_count, 'cart_items' => convert_all_elements_to_string($cart_items)];
    }
    public function apply_coupon(Request $request)
    {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];
        // $validator = Validator::make($request->all(), [
        //     'access_token' => 'required',
        //     'coupon_code' => 'required',
        // ]);
        $rules = [
            'access_token' => 'required',
            'coupon_code' => 'required',
        ];
        $messages = [
            'access_token.required' => trans('validation.access_token_required'),
            'coupon_code.required' => trans('validation.coupon_code_required'),
        ];
        $validator = Validator::make($request->all(),$rules,$messages);

        if ($validator->fails()) {
            $status = "0";
            $message = trans('validation.validation_error_occured');
            $errors = $validator->messages();
        } else {
            $user_id = $this->validateAccesToken($request->access_token);
            $coupon = Coupons::where(['coupon_code' => $request->coupon_code, 'coupon_status' => 1])->where('start_date', '<=', date('Y-m-d'))->where('coupon_end_date', '>=', date('Y-m-d'))->first();
            if ($coupon) {
                $o_data = $this->process_cart($user_id);
                if ($o_data['cart_count'] == 0) {
                    $status = "0";
                    $message = trans('validation.no_items_in_cart');
                    $errors = $validator->messages();
                    $o_data = [];
                } else if ($o_data['grand_total'] < $coupon->minimum_amount) {
                    $status = "0";
                    $message = trans('validation.minimum_order_amount_should_be') .' '. $coupon->minimum_amount;
                    $errors = $validator->messages();
                    $o_data = null;
                } else {
                    $applied_to = $coupon->applied_to;
                    $amount_type = $coupon->amount_type;
                    $amount = $coupon->coupon_amount;
                    $categories = CouponCategory::where('coupon_id', $coupon->coupon_id)->get()->toArray();
                    $categories = array_column($categories, 'category_id');
                    $discount = 0;
                    foreach ($o_data['cart_items'] as $key => $val) {
                        $det = $val['product_details'];
                        if ($applied_to == 1) {
                            if (in_array($val->product_details['category_id'], $categories)) {
                                $dis = $amount;
                                if ($amount_type == 1) {
                                    $dis = ($val->product_details['total_amount'] * $amount) / 100;
                                }
                                $det['coupon_discount'] = $dis;
                                $det['grand_total'] = $val->product_details['total_amount'] - $dis;
                                $discount += $dis;
                            } else {
                                $det['coupon_discount'] = 0;
                                $det['grand_total'] = $val->product_details['total_amount'];
                            }
                        } else {
                            $dis = $amount;
                            if ($amount_type == 1) {
                                $dis = ($val->product_details['total_amount'] * $amount) / 100;
                            }
                            $det['coupon_discount'] = 0;
                            $det['grand_total'] = $val->product_details['total_amount'];
                            $discount += $dis;
                        }
                        $o_data['cart_items'][$key]['product_details'] = $det;
                    }
                    if($o_data['grand_total'] - $discount > 0){
                        $o_data['grand_total'] =  $o_data['grand_total'] - $discount;
                    }else{
                        $d= $o_data['grand_total'] - (float)$discount;
                        $discount = $discount - ($d < 0 ? -$d : $d);
                        $o_data['grand_total'] =  0;

                    }
                    $o_data['grand_total'] = round($o_data['grand_total'],2);
                    $o_data['grand_total'] = (string)$o_data['grand_total'];
                    $o_data['discount'] = (string) $discount;
                    $o_data['default_address'] = UserAdress::get_user_default_address($user_id);
                    $o_data['address_list'] = UserAdress::get_address_list($user_id);
                }
            } else {
                $o_data = null;
                $status = "0";
                $message = trans('validation.invalid_coupon');
                $errors = $validator->messages();
            }

        }
        return response()->json(['status' => $status, 'message' => $message, 'errors' => $errors, 'oData' => (object) $o_data]);
    }
    public function delete_cart(Request $request)
    {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];
        // $validator = Validator::make($request->all(), [
        //     'access_token' => 'required',
        //     'cart_id' => 'required',
        // ]);
        $rules = [
            // 'access_token' => 'required',
            'cart_id' => 'required',
        ];
        $messages = [
            'access_token.required' => trans('validation.access_token_required'),
            'cart_id.required' => trans('validation.cart_id_required'),
        ];
        $validator = Validator::make($request->all(),$rules,$messages);

        if ($validator->fails()) {
            $status = "0";
            $message = trans('validation.validation_error_occured');
            $errors = $validator->messages();
        } else {
            // $user_id = $this->validateAccesToken($request->access_token);
            $user_id = $request->user_id ? $request->user_id :  $this->getUserId($request->access_token);
            $cart_condition = [
                "id" => $request->cart_id,
                // "user_id" => $user_id,
            ];
            if($user_id > 0){
                $cart_condition['user_id'] = $user_id;
            }else{
                $cart_condition['device_cart_id'] = $request->device_cart_id;
            }
            if (Cart::where($cart_condition)->delete()) {
                $message = trans('validation.cart_item_removed');
            }

            $o_data = $this->process_cart($user_id,$request->device_cart_id);
        }

        return response()->json(['status' => $status, 'message' => $message, 'errors' => $errors, 'oData' => $o_data]);
    }
    public function clear_cart(Request $request)
    {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];
        // $validator = Validator::make($request->all(), [
        //     'access_token' => 'required',
        // ]);
        $rules = [
            // 'access_token' => 'required',
        ];
        $messages = [
            'access_token.required' => trans('validation.access_token_required'),
        ];
        $validator = Validator::make($request->all(),$rules,$messages);

        if ($validator->fails()) {
            $status = "0";
            $message = trans('validation.validation_error_occured');
            $errors = $validator->messages();
        } else {
            // $user_id = $this->validateAccesToken($request->access_token);
            $user_id = $this->getUserId($request->access_token);
            // $cart_condition = [
            //     "user_id" => $user_id,
            // ];
            if($user_id > 0){
                $cart_condition['user_id'] = $user_id;
            }else{
                $cart_condition['device_cart_id'] = $request->device_cart_id;
            }
            if (Cart::where($cart_condition)->delete()) {
                $message = trans('validation.cart_cleared');
            }
        }

        return response()->json(['status' => $status, 'message' => $message, 'errors' => $errors, 'oData' => $o_data]);
    }
    public function payment_init(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        // $validator = Validator::make($request->all(), [
        //     'access_token' => 'required',
        //     'payment_type' => 'required|integer|min:1',
        //     'address_id' => 'required|integer|min:1',
        // ]);
        $rules = [
            'access_token' => 'required',
            'payment_type' => 'required',
            'address_id' => 'required',
        ];
        $messages = [
            'access_token.required' => trans('validation.access_token_required'),
            'payment_type.required' => trans('validation.payment_type_required'),
            'address_id.required' => 'Address is required.',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);

        if ($validator->fails()) {
            $status = "0";
            $message = $request->is_web ? $validator->messages()->first() : trans('validation.validation_error_occured');
            $errors = $validator->messages();
        } else {
            $user_id = $request->user_id  ? $request->user_id : $this->validateAccesToken($request->access_token) ;
            $address = '';
            if(isset($request->address_id)) {
                $address =  UserAdress::get_address_details($request->address_id);

            }
            $cart_details = $this->process_cart($user_id,'',$address);

            $discount = 0;

            $coupon = [];
            $coupon_id = 0;
            if ($request->coupon_code) {
                $coupon = Coupons::where(['coupon_code' => $request->coupon_code, 'coupon_status' => 1])->where('start_date', '<=', date('Y-m-d'))->where('coupon_end_date', '>=', date('Y-m-d'))->first();
            }
            if ($coupon) {
                if ($cart_details['grand_total'] < $coupon->minimum_amount) {
                } else {
                    $applied_to = $coupon->applied_to;
                    $amount_type = $coupon->amount_type;
                    $amount = $coupon->coupon_amount;
                    $categories = CouponCategory::where('coupon_id', $coupon->coupon_id)->get()->toArray();
                    $categories = array_column($categories, 'category_id');
                    $coupon_id = $coupon->coupon_id;
                    foreach ($cart_details['cart_items'] as $key => $val) {
                        $det = $val['product_details'];
                        if ($applied_to == 1) {
                            if (in_array($val->product_details['category_id'], $categories)) {
                                $dis = $amount;
                                if ($amount_type == 1) {
                                    $dis = ($val->product_details['total_amount'] * $amount) / 100;
                                }
                                $det['coupon_discount'] = $dis;
                                $det['grand_total'] = $val->product_details['total_amount'] - $dis;
                                $discount += $dis;
                            } else {
                                $det['coupon_discount'] = 0;
                                $det['grand_total'] = $val->product_details['total_amount'];
                            }
                        } else {
                            $dis = $amount;
                            if ($amount_type == 1) {
                                $dis = ($val->product_details['total_amount'] * $amount) / 100;
                            }
                            $det['coupon_discount'] = 0;
                            $det['grand_total'] = $val->product_details['total_amount'];
                            $discount += $dis;
                        }
                        $cart_details['cart_items'][$key]['product_details'] = $det;
                    }
                    
                    if($cart_details['grand_total'] - $discount > 0){
                        $cart_details['grand_total'] =  $cart_details['grand_total'] - $discount;
                    }else{
                        $d= $cart_details['grand_total'] - (float)$discount;
                        $discount = $discount - ($d < 0 ? -$d : $d);
                        $cart_details['grand_total'] =  0;

                    }
                    // $cart_details['grand_total'] = $cart_details['grand_total'] - $discount;
                    $cart_details['discount'] = $discount;
                }
            }

            $amount_to_pay = $cart_details['grand_total'];

            if ((int) $amount_to_pay == 0) {
                $message = trans('validation.your_cart_is_empty');
            } else {
                $check = \App\Models\TempOrderModel::where(['user_id' => $user_id])->first();
                if ($check) {
                    \App\Models\TempOrderModel::where(['user_id' => $user_id])->delete();
                    \App\Models\TempOrderProductsModel::where(['order_id' => $check->id])->delete();
                }
                $temp_id = $user_id . uniqid() . time();

                $settings = SettingsModel::first();
                $shipping_charge = 0;
                if (isset($settings->shipping_charge)) {
                    $shipping_charge = $settings->shipping_charge;
                }

                $temp_order = new \App\Models\TempOrderModel();
                $temp_order->user_id = $user_id;
                $temp_order->address_id = $request->address_id;
                $temp_order->total = $cart_details['cart_total'];
                $temp_order->vat = $cart_details['tax_amount'];
                $temp_order->discount = $discount;
                $temp_order->grand_total = $cart_details['grand_total'];
                $temp_order->payment_mode = $request->payment_type;
                $temp_order->temp_id = $temp_id;
                $temp_order->shipping_charge = $cart_details['delivery_charge'];
                if ($coupon && $discount) {
                    $temp_order->coupon_code = $request->coupon_code;
                    $temp_order->coupon_id = $coupon_id;
                }
                $temp_order->save();

                $temp_order_id = $temp_order->id;

                $settings = SettingsModel::first();
                $admin_commission = $settings->admin_commission;
               

                foreach ($cart_details['cart_items'] as $val) {
                    $temp_order_prds = new \App\Models\TempOrderProductsModel();
                    $temp_order_prds->order_id = $temp_order_id;

                    $temp_order_prds->product_id = $val->product_id;
                    $temp_order_prds->product_attribute_id = $val->product_attribute_id;
                    $temp_order_prds->product_type = isset($val->product_details->product_type) ? $val->product_details->product_type : 1;
                    $temp_order_prds->quantity = $val->quantity;
                    $temp_order_prds->price = $val->product_details->sale_price;
                    $temp_order_prds->discount = isset($val->product_details->coupon_discount) ? $val->product_details->coupon_discount : 0;
                    $temp_order_prds->total = $val->product_details->total_amount;
                    $temp_order_prds->grand_total = $temp_order_prds->total - $temp_order_prds->discount;
                    $temp_order_prds->vendor_id = $val->product_details->product_vender_id;
                    
                    $temp_order_prds->vendor_commission = 0;
                    $temp_order_prds->shipping_charge = 0;
                   


                    // if($val->store_commission){
                    //     $admin_commission = $val->store_commission;
                    // }
                    $temp_order_prds->admin_commission = ($admin_commission * $temp_order_prds->grand_total)/100;
                   
                    
                    $temp_order_prds->admin_commission_percentage = $admin_commission;
                    $temp_order_prds->save();
                }
                $wallet_amount_used = 0;
                if ($request->payment_type == 3 || $request->payment_type == 4 || $request->payment_type == 2) {
                    $o_data = $this->payment_init_stripe($payment_token = $temp_id, $invoice_id = $temp_id, $amount_to_pay, $wallet_amount_used, $user_id, $request->address_id, $cart_details['tax_amount'],$request);
                    $status = "1";
                    $message = "";
                } else if($request->payment_type == 20)
                {
                    try {
                        $user = User::where(['id' => $user_id])->get()->first();
                        $payment_ref = Paystack::genTranxRef();

                        $invoice_id = $temp_id;
                        $url = "https://api.paystack.co/transaction/initialize";

                        $fields = [
                            "email" => $user->email ?? config('paystack.merchantEmail'),
                            'amount' => $amount_to_pay * 100,
                            'metadata' => [
                                'custom_fields' => [
                                    [
                                        'display_name' => "Name",
                                        'variable_name' => "name",
                                        'value' => $user->name ?? $user->first_name . ' ' . $user->last_name
                                    ],
                                    [
                                        'display_name' => "Phone Number",
                                        'variable_name' => "phone_number",
                                        'value' => $user->dial_code . $user->phone_number
                                    ],
                                    [
                                        'display_name' => "Invoice ID",
                                        'variable_name' => "invoice_id",
                                        'value' => $invoice_id
                                    ]
                                ]
                            ],
                        ];

                        $fields_string = http_build_query($fields);

                        //open connection
                        $ch = curl_init();

                        //set the url, number of POST vars, POST data
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            "Authorization: Bearer ".config('paystack.secretKey'),
                            "Cache-Control: no-cache",
                        ));

                        //So that curl_exec returns the contents of the cURL; rather than echoing it
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        //execute post
                        $result = curl_exec($ch);
                        $result = json_decode($result);

                        if($result->status) {

                            $status = "1";
                            $message = $result->message;
                            $o_data["url"] = $result->data->authorization_url;
                            $o_data["authorization_url"] = $result->data->authorization_url;
                            $o_data["access_code"] = $result->data->access_code;
                            $o_data["payment_ref"] = $result->data->reference;
                            $o_data["invoice_id"] = $invoice_id;
                            $o_data["Paystack_PK"] = config('paystack.publicKey');
                            $o_data["Paytack_SK"] = config('paystack.secretKey');

                            $paymentreport = [
                                'transaction_id' => $invoice_id,
                                'payment_status' => 'P',
                                'user_id' => $user->id,
                                'ref_id' => $payment_ref,
                                'amount' => $amount_to_pay,
                                'created_at' => gmdate('Y-m-d H:i:s'),
                                'wallet_amount_used' => 0,
                            ];
                            $subTotal = $amount_to_pay;
                            $paymentreport['vat'] = $cart_details['tax_amount'];
                            \App\Models\PaymentReport::insert($paymentreport);


                        }
                        else{
                            $status = "0";
                            $message = $result->message;
                            $o_data = [];
                        }
                        
                    } catch (\Exception $e) {
                        $message = $e->getMessage();
                        $status = "0";
                    }


                }

                 else {
                    // $user = User::where(['id' => $user_id])->get()->first();
                    // if ($user->wallet_amount < $amount_to_pay) {
                    //     $status = "0";
                    //     $message = trans('validation.insufficient_wallet_balance');
                    // } else {
                    //     $wallet_amount_used = $amount_to_pay;
                    //     $paymentreport = [
                    //         'transaction_id' => $temp_id,
                    //         'payment_status' => 'P',
                    //         'user_id' => $user->id,
                    //         'ref_id' => $temp_id,
                    //         'amount' => $amount_to_pay,
                    //         'created_at' => gmdate('Y-m-d H:i:s'),
                    //         'wallet_amount_used' => $wallet_amount_used,
                    //     ];
                    //     $subTotal = $amount_to_pay;
                    //     $paymentreport['vat'] = $cart_details['tax_amount'];
                    //     \App\Models\PaymentReport::insert($paymentreport);

                    //     $res_status = $this->payment_success($temp_id);
                    //     if ($res_status === 1) {
                    //         $status = "1";
                    //         $message = trans('validation.order_placed_successfully');
                    //     } else {
                    //         $status = "0";
                    //         $message = trans('validation.soemthing_went_wrong');
                    //     }
                    // }
                }
            }

        }
        return response()->json(['status' => $status, 'message' => $message, 'errors' => (object) $errors, 'oData' => (object) $o_data]);
    }

    public function payment_init_stripe($payment_token, $invoice_id, $amount_to_pay, $payment_by_wallet, $user_id, $address_id, $tax_amount = 0,$request)
    {
        $user = User::where(['id' => $user_id])->get()->first();
        $response = array();
        $data['client_reference_id'] = $invoice_id;
        $data['product'] = "HOP";
        $data['description'] = "Product Purchase";
        $data['quantity'] = 1;
        $data['image'] = asset('/web_assets/images/logo-talents.png');
        $data['success_url'] = url('/') . '/payment_response/?sessio_id={CHECKOUT_SESSION_ID}&token=' . $payment_token;
        $data['cancel_url'] = url('/') . '/payment_cancel?sessio_id={CHECKOUT_SESSION_ID}&token={$payment_token}';
        $data['amount'] = $amount_to_pay * 100;
        $data['email'] = $user->email;
        $address = UserAdress::get_address_details($address_id);

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        if($request->is_web){
            $success_url = route('user.success',['ref'=>$invoice_id,'checkout_url'=>$request->checkout_url??'']);
            if($request->seats){
                $success_url = route('user.doggy_success',['ref'=>$invoice_id,'checkout_url'=>$request->checkout_url??'']);
            }
            if($request->quote_pay){
                $success_url = route('user.quote_success',['ref'=>$invoice_id,'checkout_url'=>$request->checkout_url??'']);
            }
            $checkout_session = \Stripe\Checkout\Session::create([
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'AED',
                        'unit_amount' => $amount_to_pay * 100,
                        'product_data' => [
                            'name' => 'Product Purchase',
                            'images' => [asset('/web_assets/images/logo-talents.png')],
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $success_url,
                'cancel_url' => $request->checkout_url,
            ]);
        }else{
            $checkout_session = \Stripe\PaymentIntent::create([
                'amount' => $amount_to_pay * 100,
                'currency' => 'AED',
                'description' => "product purchase",
                'shipping' => [
                    'name' => $user->name ?? $user->first_name . ' ' . $user->last_name,
                    'address' => [
                        'line1' => $address->address ?? '',
                        'city' => $address->street ?? '',
                        'state' => $address->location ?? '',
                        'country' => 'UAE',
                    ],
                ],
            ]);
        }

        $data['session_id'] = $checkout_session->id;
        $ref = $checkout_session->id;
        $paymentreport = [
            'transaction_id' => $invoice_id,
            'payment_status' => 'P',
            'user_id' => $user->id,
            'ref_id' => $ref,
            'amount' => $amount_to_pay,
            'created_at' => gmdate('Y-m-d H:i:s'),
            'wallet_amount_used' => $payment_by_wallet,
        ];
        $subTotal = $amount_to_pay;
        $paymentreport['vat'] = $tax_amount;
        \App\Models\PaymentReport::insert($paymentreport);

        $payment_ref = $checkout_session->client_secret;
        if($request->is_web){
            $url = $checkout_session->url;
            return compact('invoice_id', 'url');
        }
        return compact('invoice_id', 'payment_ref');
    }

    public function payment_init_paystack($payment_token, $invoice_id, $amount_to_pay, $payment_by_wallet, $user_id, $address_id, $tax_amount = 0,$request)
    {
        $access_code = "";
    $payment_ref= "";
    $authorization_url = "";
    $Paystack_PK = config('paystack.publicKey');
    $Paytack_SK = config('paystack.secretKey');
        try {
                        $user = User::where(['id' => $user_id])->get()->first();
                        $payment_ref = Paystack::genTranxRef();

                        $invoice_id = $invoice_id;
                        $url = "https://api.paystack.co/transaction/initialize";

                        $fields = [
                            "email" => $user->email ?? config('paystack.merchantEmail'),
                            'amount' => $amount_to_pay * 100,
                            "ref"=> $invoice_id,
                            'metadata' => [
                                'custom_fields' => [
                                    [
                                        'display_name' => "Name",
                                        'variable_name' => "name",
                                        'value' => $user->name ?? $user->first_name . ' ' . $user->last_name
                                    ],
                                    [
                                        'display_name' => "Phone Number",
                                        'variable_name' => "phone_number",
                                        'value' => $user->dial_code . $user->phone_number
                                    ],
                                    [
                                        'display_name' => "Invoice ID",
                                        'variable_name' => "invoice_id",
                                        'value' => $invoice_id
                                    ]
                                ]
                            ],
                        ];

                        $fields_string = http_build_query($fields);

                        //open connection
                        $ch = curl_init();

                        //set the url, number of POST vars, POST data
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            "Authorization: Bearer ".config('paystack.secretKey'),
                            "Cache-Control: no-cache",
                        ));

                        //So that curl_exec returns the contents of the cURL; rather than echoing it
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        //execute post
                        $result = curl_exec($ch);
                        $result = json_decode($result);



                        if($result->status) {

                            $status = "1";
                            $message = $result->message;
                            $o_data["url"] = $result->data->authorization_url;
                            $o_data["authorization_url"] = $result->data->authorization_url;
                            $o_data["access_code"] = $result->data->access_code;
                            $o_data["payment_ref"] = $result->data->reference;
                            $o_data["invoice_id"] = $invoice_id;
                            $o_data["Paystack_PK"] = config('paystack.publicKey');
                            $o_data["Paytack_SK"] = config('paystack.secretKey');
                            $access_code = $result->data->access_code;
                            $invoice_id  = $invoice_id; 
                            $payment_ref = $result->data->reference;
                            $authorization_url = $result->data->authorization_url;
                            $Paystack_PK = config('paystack.publicKey');
                            $Paytack_SK = config('paystack.secretKey');
                            $url = $result->data->authorization_url;

                            $paymentreport = [
                                'transaction_id' => $invoice_id,
                                'payment_status' => 'P',
                                'user_id' => $user->id,
                                'ref_id' => $payment_ref,
                                'amount' => $amount_to_pay,
                                'created_at' => gmdate('Y-m-d H:i:s'),
                                'wallet_amount_used' => 0,
                                'payment_for' => $request->type??0,
                            ];
                            $subTotal = $amount_to_pay;
                            if(!empty($cart_details['tax_amount']))
                            {
                             $paymentreport['vat'] = $cart_details['tax_amount']??0;   
                            }
                            \App\Models\PaymentReport::insert($paymentreport);
                        }
                        else{
                            $status = "0";
                            $message = $result->message;
                            $o_data = [];
                        }
                        
                    } catch (\Exception $e) {
                        $message = $e->getMessage();
                        $status = "0";
                    }

        return compact('access_code','invoice_id', 'payment_ref','authorization_url','Paystack_PK','Paytack_SK','url');
    }

    public function stripe(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
       
        $rules = [
            'access_token' => 'required',
            'amount' => 'required',
        ];
        $messages = [
            'access_token.required' => trans('validation.access_token_required'),
            'amount.required' => 'Amount is required.',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);

        if ($validator->fails()) {
            $status = "0";
            $message = trans('validation.validation_error_occured');
            $errors = $validator->messages();
            return response()->json(['status' => $status, 'message' => $message, 'errors' => (object) $errors, 'oData' => (object) $o_data]);
        }
        $user_id = $this->validateAccesToken($request->access_token);

        $payment_token = $user_id.uniqid().time();
        $o_data =  $this->payment_init_stripe($payment_token,$payment_token, $request->amount, 0, $user_id, $request->address_id, 0,$request);
        $status = "1";
        $message = 'Payment Init Successfully';
        return response()->json(['status' => $status, 'message' => $message, 'errors' => (object) $errors, 'oData' => (object) $o_data]);

    }

    public function paystack(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
       
        $rules = [
            'access_token' => 'required',
            'amount' => 'required',
        ];
        $messages = [
            'access_token.required' => trans('validation.access_token_required'),
            'amount.required' => 'Amount is required.',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);

        if ($validator->fails()) {
            $status = "0";
            $message = trans('validation.validation_error_occured');
            $errors = $validator->messages();
            return response()->json(['status' => $status, 'message' => $message, 'errors' => (object) $errors, 'oData' => (object) $o_data]);
        }
        $user_id = $this->validateAccesToken($request->access_token);

        $payment_token = $user_id.uniqid().time();
        $o_data =  $this->payment_init_paystack($payment_token,$payment_token, $request->amount, 0, $user_id, $request->address_id, 0,$request);
        $status = "1";
        $message = 'Payment Init Successfully';
        return response()->json(['status' => $status, 'message' => $message, 'errors' => (object) $errors, 'oData' => (object) $o_data]);

    }
    public function payment_response()
    {

    }
    public function payment_cancel()
    {

    }
    public function place_order(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        // $validator = Validator::make($request->all(), [
        //     'access_token' => 'required',
        //     'invoice_id' => 'required',
        // ]);
        $rules = [
            'access_token' => 'required',
            'invoice_id' => 'required',
        ];
        $messages = [
            'access_token.required' => trans('validation.access_token_required'),
            'invoice_id.required' => trans('validation.invoice_id_required'),
        ];
        $validator = Validator::make($request->all(),$rules,$messages);


        if ($validator->fails()) {
            $status = "0";
            $message = trans('validation.validation_error_occured');
            $errors = $validator->messages();
        } else {
            $user_id = !$request->user_id  ? $this->validateAccesToken($request->access_token) : $request->user_id;
            $res_status = $this->payment_success($request->invoice_id);
            if ($res_status != 0) {
                $status = "1";
                $o_data = $res_status;
                $message = trans('validation.order_placed_successfully');
            } else {
                $status = "0";
                $message = trans('validation.soemthing_went_wrong');
            }
        }
        return response()->json(['status' => $status, 'message' => $message, 'errors' => (object) $errors, 'oData' => (object) $o_data]);
    }
    private function payment_success($invoice_id)
    {
        try {

            $data = \App\Models\TempOrderModel::where(['temp_id' => $invoice_id])->first();
            if ($data) {

                $order = new OrderModel();
                $order->invoice_id = $invoice_id;
                $order->user_id = $data->user_id;
                $order->address_id = $data->address_id;
                $order->total = $data->total;
                $order->vat = $data->vat;
                $order->discount = $data->discount;
                $order->grand_total = $data->grand_total;
                $order->payment_mode = $data->payment_mode;
                $order->status = 0;
                $order->booking_date = gmdate('Y-m-d H:i:s');

                $order->admin_commission = $data->admin_commission;
                $order->vendor_commission = $data->vendor_commission;
                $order->shipping_charge = $data->shipping_charge;
                $order->created_at = gmdate('Y-m-d H:i:s');
                $order->user_id = $data->user_id;
                $order->coupon_code = $data->coupon_code;
                $order->coupon_id = $data->coupon_id;

                $order->save();
                // dd('q');
                $order_id = $order->order_id;

                if ($data->coupon_code) {
                    $chist = new CouponHistory();
                    $chist->order_id = $order_id;
                    $chist->coupon_id = $data->coupon_id;
                    $chist->user_id = $data->user_id;
                    $chist->coupon_code = $data->coupon_code;
                    $chist->discount = $data->discount;
                    $chist->save();
                }

                // dd($order_id);
                $order_prds_data = \App\Models\TempOrderProductsModel::where('order_id', $data->id)->get();
                $total_qty = 0;
                $total_items_qty = 0;
                $influnecers = [];
                $influencer_users = [];
                foreach ($order_prds_data as $key=>$val) {
                    $total_qty += 1;
                    $total_items_qty += $val->quantity;
                    $order_prds = new OrderProductsModel();
                    $order_prds->order_id = $order_id;
                    $order_prds->product_id = $val->product_id;
                    $order_prds->product_attribute_id = $val->product_attribute_id;
                    $order_prds->product_type = $val->product_type;
                    $order_prds->quantity = $val->quantity;
                    $order_prds->price = $val->price;
                    $order_prds->discount = $val->discount;
                    $order_prds->total = $val->total;
                    $order_prds->grand_total = $val->grand_total;
                    $order_prds->admin_commission = $val->admin_commission;

                    $order_prds->admin_commission_percentage = $val->admin_commission_percentage;
                   
                    $order_prds->vendor_id = $val->vendor_id;
                    $order_prds->order_status = 1;
                    $order_prds->admin_commission = $val->admin_commission;
                    $order_prds->vendor_commission = $val->vendor_commission;
                    $order_prds->shipping_charge = $val->shipping_charge;

                    $order_prds->save();
                }
                $order->total_qty = $total_qty;
                $order->total_items_qty = $total_items_qty;
                $order->save();

                \App\Models\TempOrderModel::where(['temp_id' => $invoice_id])->delete();
                \App\Models\TempOrderProductsModel::where(['id' => $data->id])->delete();

                $payObj = \App\Models\PaymentReport::where('transaction_id', $invoice_id)->get()->first();
                $payObj->payment_status = 'A';
                $payObj->save();
                $users = User::find($payObj->user_id);
                $wallet_amount_used = $payObj->wallet_amount_used;
                if ($wallet_amount_used > 0) {
                    $users = User::find($payObj->user_id);
                    if ($users) {
                        $w_data = [
                            'user_id' => $users->id,
                            'wallet_amount' => $wallet_amount_used,
                            'pay_type' => 'debited',
                            'description' => 'Used for cart checkout',
                        ];
                        if (wallet_history($w_data)) {
                            $users->wallet_amount = $users->wallet_amount - $wallet_amount_used;
                            $users->save();
                        }
                    }
                }
                Cart::where(['user_id' => $users->id])->delete();
                Cart::where(['user_id' => $users->id])->delete();

                $title = trans('validation.order_placed_successfully');
                $description = trans('validation.your_order_has_been_placed_successfully');
                $notification_id = time();
                $ntype = 'order_placed';
                if (!empty($users->firebase_user_key)) {
                    $notification_data["Nottifications/" . $users->firebase_user_key . "/" . $notification_id] = [
                        "title" => $title,
                        "description" => $description,
                        "notificationType" => $ntype,
                        'createdDate'     => $order->created_at,
                        "orderId" => (string) $order_id,
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
                            "orderId" => (string) $order_id,
                            "imageURL" => "",
                        ]);
                }
                 adminNotification($order_id,\App\Models\DbNotification::Order,'Order received','New Order received');


                $name = $users->name ?? $users->first_name . ' ' . $users->last_name;
               
               if (config('global.server_mode') == 'local') {
                    \Artisan::call("send:send_order_email --uri=" . urlencode($users->email) . " --uri2=" . $order_id . " --uri3=" . urlencode($name) . " --uri4=" . $users->id);
                } else {
                    exec("php " . base_path() . "/artisan send:send_order_email --uri=" . urlencode($users->email) . " --uri2=" . $order_id . " --uri3=" . urlencode($name) . " --uri4=" . $users->id . " > /dev/null 2>&1 & ");
                }

            }
            $orderid =  config('global.sale_order_prefix').date(date('Ymd', strtotime($order->created_at))).$order_id;
            return $orderid;
        } catch (\Exception $e) {
            printr($e->getMessage());
            return 0;
        }
    }
}
