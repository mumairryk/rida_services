<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\ModaSubCategories;
use App\Models\ProductLikes;
use App\Models\ProductModel;
use App\Models\Stores;
use App\Models\User;
use App\Models\VendorModel;
use DB;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
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
    function list(Request $request) {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];
        // $validator = Validator::make($request->all(), [

        // ]);

        // if ($validator->fails()) {
        //     $message = "Validation error occured";
        //     $errors = $validator->messages();
        //     return response()->json([
        //         'status' => "0",
        //         'message' => $message,
        //         'errors' => (object) $errors,
        //     ], 200);
        // }

        $access_token = $request->access_token;
        $limit = isset($request->limit) ? $request->limit : 10;
        $offset = isset($request->page) ? ($request->page - 1) * $request->limit : 0;

        if($request->is_featured){
            $where['is_featured'] = 1;

        }
        $where['deleted'] = 0;
        $where['product_status'] = 1;

        $filter['sort'] = $request->sort;
        $filter['search_text'] = $request->search_text;
        $filter['parent_category_id'] = $request->category_id;

        $list = ProductModel::products_list($where, $filter, $limit, $offset)->get();
        $user = User::where('user_access_token', $access_token)->first();
        $products = $this->product_inv($list, $user);
        $o_data['list'] = $products;
        $o_data['currency_code'] =  config('global.default_currency_code');
        $o_data = convert_all_elements_to_string($o_data);
        return response()->json(['status' => $status, 'message' => $message, 'errors' => (object) $errors, 'oData' => $o_data], 200);
    }

    function category_list(Request $request) {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];

        $access_token = $request->access_token;
        
        $where['deleted'] = 0;
        $where['product_status'] = 1;

        $filter['search_text'] = $request->search_text;

        
        $user = User::where('user_access_token', $access_token)->first();
        $categories = DB::table('product_category')->select('category.id','category.name')->join('category','category.id','product_category.category_id')->distinct('category.id')->get();

        $filter['is_featured'] = 1;
        $list = ProductModel::products_list($where, $filter, 5, 0)->get();
        $products = $this->product_inv($list, $user);
        $o_data['featured_list'] = $products;

        $filter['is_featured'] = 0;
        foreach($categories as $key=> $val){
            $filter['parent_category_id'] = $val->id;
            $list = ProductModel::products_list($where, $filter, 5, 0)->get();
            $categories[$key]->products = $this->product_inv($list, $user);
        }
        $o_data['category_list'] = $categories;

        $o_data['currency_code'] =  config('global.default_currency_code');
        $o_data = convert_all_elements_to_string($o_data);
        return response()->json(['status' => $status, 'message' => $message, 'errors' => (object) $errors, 'oData' => $o_data], 200);
    }

    function fav_list(Request $request) {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];
        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
        ]);

        if ($validator->fails()) {
            $message = "Validation error occured";
            $errors = $validator->messages();
            return response()->json([
                'status' => "0",
                'message' => $message,
                'errors' => (object) $errors,
            ], 200);
        }

        $user_id = $this->validateAccesToken($request->access_token);
        $limit = isset($request->limit) ? $request->limit : 10;
        $offset = isset($request->page) ? ($request->page - 1) * $request->limit : 0;

        $where['deleted'] = 0;
        $where['product_status'] = 1;
        $where['product_likes.user_id'] = $user_id;

        $list = ProductModel::products_fav_list($where, $limit, $offset)->get();
        if($list->count() > 0){
            $user = User::find($user_id);
            $products = $this->product_inv($list, $user);
            $o_data['list'] = convert_all_elements_to_string($products);
        }        
        return response()->json(['status' => $status, 'message' => $message, 'errors' => (object) $errors, 'oData' => (object)$o_data], 200);
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
    public function product_like_dislike(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];

        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
            'product_id' => 'required|numeric',
            'product_attribute_id'=> 'required'
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $user_id = $this->validateAccesToken($request->access_token);
            $product_id = $request->product_id;
            $check_exist = ProductLikes::where(['product_id' => $product_id, 'user_id' => $user_id,'product_attribute_id'=>$request->product_attribute_id])->get();
            if ($check_exist->count() > 0) {
                ProductLikes::where(['product_id' => $product_id, 'user_id' => $user_id,'product_attribute_id'=>$request->product_attribute_id])->delete();
                $status = "1";
                $message = "disliked";
            } else {
                $like = new ProductLikes();
                $like->product_id = $product_id;
                $like->user_id = $user_id;
                $like->product_attribute_id = $request->product_attribute_id;
                $like->created_at = gmdate('Y-m-d H:i:s');
                $like->save();
                if ($like->id > 0) {
                    $status = "1";
                    $message = "liked";
                } else {
                    $message = "faild to like";
                }
            }
        }
        return response()->json(['status' => $status, 'error' => (object)$errors, 'message' => $message, 'oData' => (object)$o_data], 200);
    }

    public function details(Request $request)
    {

        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];
        $product = [];
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|numeric|min:0|not_in:0',
        ]);

        if ($validator->fails()) {
            $message = "Validation error occured";
            $errors = $validator->messages();
            return response()->json([
                'status' => "0",
                'message' => $message,
                'errors' => (object) $errors,
            ], 200);
        }

        $access_token = $request->access_token;
        $user = User::where('user_access_token', $access_token)->first();
        $product_id = $request->product_id;
        $product_variant_id = $request->product_variant_id;

        $sattr = $request->sattr;
        $sattr = json_decode($request->sattr, true);
        $return_status = true;

        if (!$product_variant_id) {
            list($return_status, $product_attribute_id, $message) = ProductModel::get_product_attribute_id_from_attributes($sattr, $product_id);
            $product_variant_id = $product_attribute_id;
        }

        if (!$return_status) {
            $status = "0";
            $message = "Invalid data passed";
            return response()->json([
                'status' => "0",
                'message' => $message,
                'errors' => (object) $errors,
            ], 200);
        }

        list($status, $product, $message) = ProductModel::getProductVariant($product_id, $product_variant_id);

        if ($status && !empty($product)) {
            $product = process_product_data_api($product);
            $product['is_liked'] = 0;
            if ($user) {
                $is_liked = ProductLikes::where(['product_id' => $product['product_id'], 'user_id' => $user->id])->count();
                if ($is_liked) {
                    $product['is_liked'] = 1;
                }
            }
            $product['share_link'] = url("share/product/" . $product_id . "/" . $product['product_variant_id']);


            $product_selected_attributes = ProductModel::getProductVariantAttributes($product['product_variant_id']);

            $product_variations = [];
            $product_attributes = ProductModel::getProductAttributeVals([$product['product_id']]);
            foreach ($product_attributes as $attr_row) {
                if (array_key_exists($attr_row->attribute_id, $product_variations) === false) {
                    $product_variations[$attr_row->attribute_id] = [
                        'product_attribute_id' => $attr_row->product_attribute_id,
                        'attribute_id' => $attr_row->attribute_id,
                        'attribute_id' => $attr_row->attribute_id,
                        'attribute_type' => $attr_row->attribute_type,
                        'attribute_name' => $attr_row->attribute_name,
                        'attribute_values' => [],
                    ];
                    if ($attr_row->attribute_type === 'radio_button_group') {
                        $product_variations[$attr_row->attribute_id]['help_text_start'] = $attr_row->attribute_value_label;
                    }
                }
                if ($attr_row->attribute_type === 'radio_button_group') {
                    $product_variations[$attr_row->attribute_id]['help_text_end'] = $attr_row->attribute_value_label;
                }
                if (array_key_exists($attr_row->attribute_values_id, $product_variations[$attr_row->attribute_id]['attribute_values']) === false) {
                    $is_selected = 0;
                    if (array_key_exists($attr_row->attribute_id, $product_selected_attributes) && ($product_selected_attributes[$attr_row->attribute_id] == $attr_row->attribute_values_id)) {
                        $is_selected = 1;
                    }
                    $product_variations[$attr_row->attribute_id]['attribute_values'][$attr_row->attribute_values_id] = [
                        'attribute_value_id' => $attr_row->attribute_values_id,
                        'attribute_value_name' => $attr_row->attribute_values,
                        'is_selected' => $is_selected,
                    ];
                    if ($attr_row->attribute_value_in == 2) {
                        $product_variations[$attr_row->attribute_id]['attribute_values'][$attr_row->attribute_values_id]['attribute_value_color'] = $attr_row->attribute_color;
                    }
                    if ($attr_row->attribute_type === 'radio_image') {
                        $t_image = $attr_row->attribute_value_image;

                        $product_variations[$attr_row->attribute_id]['attribute_values'][$attr_row->attribute_values_id]['attribute_value_image'] = $t_image;
                    }
                }
            }

            $product['product_variations'] = [];
            if (!empty($product_variations)) {
                $t_variations = array_values($product_variations);
                foreach ($t_variations as $k => $v) {
                    $t_variations[$k]['attribute_values'] = array_values($t_variations[$k]['attribute_values']);
                }
                $product["product_variations"] = $t_variations;
            }

        } else {
            $status = "0";
            $product = [];
            $message = "No details found.";
        }
        
        $product = convert_all_elements_to_string($product);
        $o_data['product_details'] = $product;
        return response()->json(['status' => (string)$status, 'message' => $message, 'errors' => (object) $errors, 'oData' => $product], 200);
    }

    
}
