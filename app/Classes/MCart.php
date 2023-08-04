<?php
namespace App\Classes;
use App\Models\Cart;
use App\Models\SettingsModel;
use App\Models\CouponUsage;
use App\Models\CouponCategory;
use App\Models\CouponProducts;
use App\Models\DeligateModel;
class MCart{

public $key='';
public $cart_message = '';
public $device_cart_id =  '';
public $user_id   =  '';
public $cart_products = [];
public $cart_status = "0";
public $item_total  = 0;
public $delivery_charge = 0;
public $tax = 0 ;
public $tax_percentage = 0;
public $grand_total = 0;
public $cart_count = 0;
public $coupon_data = [];
public $coupon_applied_status= 0;
public $coupon_products = [];
public $coupon_categories = [];
public $total_coupon_discount = 0;
public $applied_coupon_id = 0;
public $store_id;
public $deligate_id;

  function getConfig(){
    return $this->key;
  }
  function init(){
      $this->cart_message = "init";
  }

  function get_cart(){
      $condition  = [];
      if($this->user_id){
          $condition['user_id'] = $this->user_id;
      }else{
          $condition['device_cart_id'] = $this->device_cart_id;
          $condition['user_id'] = 0;
      }
      $cart_products = Cart::get_cart_products($condition);

      if($cart_products->count() > 0){
          $cart_products  = $cart_products->toArray();
          if(!empty($this->coupon_data)){
              if($this->coupon_data->applied_to == 1){ //applied to category
                  $category_list = CouponCategory::where(['coupon_id'=>$this->coupon_data->coupon_id])->get()->toArray();
                  if(!empty($category_list)){
                      $this->coupon_categories = array_column($category_list,'category_id');
                  }
              }else if($this->coupon_data->applied_to == 2){ //applied to product
                  $product_list = CouponProducts::where(['coupon_id'=>$this->coupon_data->coupon_id])->get()->toArray();
                  if(!empty($product_list)){
                      $this->coupon_products = array_column($product_list,'product_id');
                  }
              }
          }
          $item_total = 0;
          foreach($cart_products as $itemKey=>$item){
            $this->store_id =  $item['store_id'];
              $image_list = [];
              $images = explode(",",$item['image']);
              $images = array_filter($images);
              foreach($images as $key){
                  if($key){
                      $image_list[] = get_uploaded_image_url($key,'product_image_upload_dir');
                  }
              }
              if(empty($image_list)){
                  $image_list[] = url('/').'/admin-assets/assets/img/logo.png';
              }
              $cart_products[$itemKey]['image_list'] = $image_list;
              $cart_products[$itemKey]['default_image'] = $image_list[0]??url('/').'/admin-assets/assets/img/logo.png';

              //cart calculations
              $cart_row_total = ($item['quantity'] * $item['sale_price']);
              $cart_products[$itemKey]['sub_total'] = $cart_row_total;
              $item_total+= $cart_row_total;

              //coupon calculations
              $coupon_discount = 0;
              if(in_array($item['product_id'],$this->coupon_products)){
                  $this->coupon_applied_status = 1;
                  if($this->coupon_data->amount_type == 1){ //percentage Discount
                      $coupon_discount = ( $cart_row_total * ( $this->coupon_data->coupon_amount / 100 ) );
                  }else if($this->coupon_data->amount_type == 1){ //fixed Discount
                        $coupon_discount = $this->coupon_data->coupon_amount;
                  }
              }

              //print_r(json_decode($item['category_selected']));
              $combinations = array_intersect( explode(",",$item['category_selected']), $this->coupon_categories );

              if(!empty($combinations)){
                   $this->coupon_applied_status = 1;
                   if($this->coupon_data->amount_type == 1){ //percentage Discount
                       $coupon_discount = ( $cart_row_total * ( $this->coupon_data->coupon_amount / 100 ) );
                   }else if($this->coupon_data->amount_type == 1){ //fixed Discount
                         $coupon_discount = $this->coupon_data->coupon_amount;
                   }
              }
              if($cart_row_total <= $coupon_discount){
                  $coupon_discount = $cart_row_total;
              }
              $cart_products[$itemKey]['discounted_sub_total'] = $cart_row_total - $coupon_discount;
              $cart_products[$itemKey]['coupon_discounted_amount'] = $coupon_discount;
              $this->total_coupon_discount+= $coupon_discount;

          }
          $this->cart_products = $cart_products;
          $this->cart_status   = "1";

          $settings = SettingsModel::first();
          $tax_percentage = 0;
          $delivery_charge = 0;
          if (isset($settings->tax_percentage)) {
              $tax_percentage = $settings->tax_percentage;
          }
          if ($this->deligate_id ) {
              $deligate = DeligateModel::find($this->deligate_id);
              $delivery_charge = $deligate->shipping_charge;
          }
          $tax_amount = ($item_total * $tax_percentage) / 100;
          $this->cart_count = count($this->cart_products);

          $this->delivery_charge = $delivery_charge;
          $this->tax = $tax_amount;
          $this->tax_percentage = $tax_percentage;
          $this->item_total = $item_total;
          if($this->total_coupon_discount > $item_total){
              $this->total_coupon_discount = $item_total;
          }
          $this->grand_total = $this->tax + $this->delivery_charge + $this->item_total - $this->total_coupon_discount;

          if(!empty($this->coupon_data) && $this->coupon_applied_status == 0){
              $this->cart_status = "0";
              $this->cart_message = "Coupon canot be applied to your cart items";
          }
          
          if($this->coupon_applied_status == 1){
              $this->applied_coupon_id = $this->coupon_data->coupon_id;
              $this->cart_message = "Coupon applied successfully";
          }

      }else{
          $this->cart_message = 'No items in your cart';
      }
      return $this->cart_products;
  }

  public function combine_cart($user_id,$device_cart_id){
      $latest_store_id = 0;
      $user_latest_entry = Cart::where(['user_id'=>$user_id])->orderBy('id','desc')->get();
      $device_latest_entry = Cart::where(['user_id'=>0,'device_cart_id'=>$device_cart_id])->orderBy('id','desc')->get();
      if($user_latest_entry->count() > 0 && $device_latest_entry->count() > 0){
          $user_latest_entry = $user_latest_entry->first();
          $device_latest_entry = $device_latest_entry->first();
          if($user_latest_entry->created_at < $device_latest_entry->created_at){
              $latest_store_id = $device_latest_entry->store_id;
          }else{
              $latest_store_id = $user_latest_entry->store_id;
          }
      }else if($device_latest_entry->count() > 0 && $user_latest_entry->count() ==0 ){
           $device_latest_entry= $device_latest_entry->first();
           $latest_store_id = $device_latest_entry->store_id;
      }else if($device_latest_entry->count() == 0 && $user_latest_entry->count() > 0 ){
           $user_latest_entry= $user_latest_entry->first();
           $latest_store_id = $user_latest_entry->store_id;
      }

      //Remove all cart items except latest store id
      //$this->store_id =  $latest_store_id;
      Cart::where(['user_id'=>$user_id])->where('store_id','!=',$latest_store_id)->delete();
      Cart::where(['user_id'=>0,'device_cart_id'=>$device_cart_id])->where('store_id','!=',$latest_store_id)->delete();

      $cart_items = [];
      $similarRow = [];

      $latest_entry_date ='';
      $user_cart_items  = Cart::Where(['user_id'=>$user_id])->where('device_cart_id','!=',$device_cart_id)->orderBy('id','desc')->get();
      foreach($user_cart_items as $row){
          $c_row = Cart::where('id','!=',$row->id)->where(['user_id'=>0,'device_cart_id'=>$device_cart_id,'product_id'=>$row->product_id,'product_attribute_id'=>$row->product_attribute_id])->get();
          if( $c_row->count() > 0){
              $c_row = $c_row->first();
              Cart::where(['id'=>$row->id])->update(
                  [
                      'quantity' => $c_row->quantity + $row->quantity,
                      'store_id' => $row->store_id,
                      'updated_at'=> gmdate('Y-m-d H:i:s')
                  ]
              );
              Cart::where('id','=',$c_row->id)->delete();
          }
      }
      $user_cart_items  = Cart::Where(['user_id'=>0])->where('device_cart_id','=',$device_cart_id)->orderBy('id','desc')->get();
      foreach($user_cart_items as $row){
          $c_row = Cart::where('id','!=',$row->id)->where(['user_id'=>$user_id,'device_cart_id'=>$device_cart_id,'product_id'=>$row->product_id,'product_attribute_id'=>$row->product_attribute_id])->get();
          if( $c_row->count() > 0){
              $c_row = $c_row->first();
              Cart::where(['id'=>$row->id])->update(
                  [
                      'quantity' => $c_row->quantity + $row->quantity,
                      'store_id' => $row->store_id,
                      'updated_at'=> gmdate('Y-m-d H:i:s')
                  ]
              );
              Cart::where('id','=',$c_row->id)->delete();
          }
      }
      Cart::where(['user_id'=>0,'device_cart_id'=>$device_cart_id])->update(['user_id'=>$user_id]);
      return true;
  }

}
