<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;


if (!function_exists('is_active')) {
    function is_active($route)
    {
        return  \Route::currentRouteName() ==  $route ? 'active' : '';
    }
}

if (!function_exists('contact_details')) {
    function contact_details()
    {
        return  null;//\App\ContactUsSetting::first();
    }
}

if (! function_exists('time_to_uae') ) {
    function time_to_uae($date, $format="Y-m-d H:i:s")
    {
        return date($format, strtotime(' +4 hours', strtotime($date)));
    }
}
if (!function_exists('get_otp')) {
    function get_otp()
    {
        return 1111;
        return  rand(pow(10, 4 - 1), pow(10, 4) - 1);
    }
}


if (! function_exists('get_storage_path') ) {
    function get_storage_path( $filename='', $dir='' )
    {
        if ( !empty($filename) ) {

            $upload_dir = config('global.upload_path');
            if (! empty($dir) ) {
                $dir= config("global.{$dir}");
            }
            if ( \Storage::disk(config('global.upload_bucket'))->exists($dir.$filename) ) {
               return \Storage::url("{$dir}{$filename}");
           }
        }


        return '';
    }
}
if (! function_exists('get_uploaded_image_url') ) {
    function get_uploaded_image_url( $filename='', $dir='', $default_file='placeholder.png' )
    {

        if ( !empty($filename) ) {

            $upload_dir = config('global.upload_path');
            if (! empty($dir) ) {
                $dir= config("global.{$dir}");
               
            }

            if ( \Storage::disk(config('global.upload_bucket'))->exists($dir.$filename) ) {
                // return 'https://d3k2qvqsrjpakn.cloudfront.net/moda/public'.\Storage::url("{$dir}{$filename}");
                return \Storage::disk(config('global.upload_bucket'))->url($dir.$filename);
                //return asset(\Storage::url("{$dir}{$filename}"));
           }else{

            return asset(\Storage::url("{$dir}{$filename}"));
           }
        }
        if ( !empty($default_file) ) {
            if (! empty($dir) ) {
                $dir= config("global.{$dir}");
            }
            $default_file = asset(\Storage::url("{$dir}{$default_file}"));
        }
        if (! empty($default_file) ) {
            return $default_file;
        }


        return \Storage::url("logo.png");
    }
}
if (! function_exists('time_ago') ) {
    function time_ago( $datetime, $now=NULL, $timezone='Etc/GMT' )
    {
        if (! $now ) {
            $now = time();
        }
        $timezone_user  = new DateTimeZone($timezone);
        $date           = new DateTime($datetime, $timezone_user);
        $timestamp      = $date->getTimestamp();
        $timespan       = explode(', ', timespan($timestamp, $now));
        $timespan       = $timespan[0] ?? '';
        $timespan       = strtolower($timespan);

        if (! empty($timespan) ) {
            if ( stripos($timespan, 'second') !== FALSE ) {
                $timespan = 'few seconds ago';
            } else {
                $timespan .= " ago";
            }
        }

        return $timespan;
    }
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

if (! function_exists('get_date_in_timezone') ) {
    function get_date_in_timezone($date, $format="d-M-Y h:i a",$timezone='',$server_time_zone="Etc/GMT")
    {
        if($timezone == ''){
            $timezone = config('global.date_timezone');
        }
        try {
            $timezone_server    = new DateTimeZone($server_time_zone);
            $timezone_user      = new DateTimeZone($timezone);
        }
        catch (Exception $e) {
            $timezone_server    = new DateTimeZone($server_time_zone);
            $timezone_user      = new DateTimeZone($server_time_zone);
        }


        $dt = new DateTime($date, $timezone_server);

        $dt->setTimezone($timezone_user);

        return $dt->format($format);
    }
}
function public_url()
{
    if (config('app.url') == 'http://127.0.0.1:8000') {
        return str_replace('/public', '', config('app.url'));
    }
    return config('app.asset_url');
}

function image_upload($request,$model,$file_name, $mb_file_size = 25)
{
    if(empty($model)) $model = 'category';
    if($request->file($file_name ))
    {
        $file = $request->file($file_name);
        return  file_save($file,$model, $mb_file_size);
    }
    return ['status' =>false,'link'=>null,'message' => 'Unable to upload file'];
}

if (! function_exists('array_combination') ) {
    function array_combination($arrays, $i = 0)
    {
        if (!isset($arrays[$i])) {
            return array();
        }
        if ($i == count($arrays) - 1) {
            return $arrays[$i];
        }

        // get combinations from subsequent arrays
        $tmp = array_combination($arrays, $i + 1);

        $result = array();

        // concat each array from tmp with each element from $arrays[$i]
        foreach ($arrays[$i] as $v) {
            foreach ($tmp as $t) {
                $result[] = is_array($t) ?
                    array_merge(array($v), $t) :
                    array($v, $t);
            }
        }

        return $result;
    }
}

function file_save($file,$model,$mb_file_size=25)
{
     try {
        $model = str_replace('/','',$model);
        //validateSize
        $precision = 2;
        $size = $file->getSize();
        $size = (int) $size;
        $base = log($size) / log(1024);
        $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');
        $dSize = round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];

        $aSizeArray = explode(' ', $dSize);
        if ($aSizeArray[0] > $mb_file_size && ($aSizeArray[1] == 'MB' || $aSizeArray[1] == 'GB' || $aSizeArray[1] == 'TB')) {
            return ['status' =>false,'link'=>null,'message' => 'Image size should be less than equal '.$mb_file_size.' MB'];
        }
        // rename & upload files to upload folder
        $name =  uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = public_path() . '/uploads/'.$model.'/';
        $file->move($path, $name);

        $image_url = '/uploads/'.$model.'/' . $name;

        return ['status' =>true,'link'=>$image_url,'message' => 'file uploaded'];

    } catch (\Exception $e) {
        return ['status' =>false,'link'=> null ,'message' => $e->getMessage()];
    }
}
if (! function_exists('deleteFile') ) {
    function deleteFile($path)
    {
        try {
            $root_path = base_path() . $path;
            if (file_exists($root_path))
                unlink($root_path);
        } catch (\Exception $e) {
            return false;
        }
    }
}

function printr($data){
  echo '<pre>';
  var_dump($data);
  echo '</pre>';
}
function url_title($str, $separator = '-', $lowercase = FALSE)
{
    if ($separator == 'dash')
    {
        $separator = '-';
    }
    else if ($separator == 'underscore')
    {
        $separator = '_';
    }

    $q_separator = preg_quote($separator);

    $trans = array(
        '&.+?;'                 => '',
        '[^a-z0-9 _-]'          => '',
        '\s+'                   => $separator,
        '('.$q_separator.')+'   => $separator
    );

    $str = strip_tags($str);

    foreach ($trans as $key => $val)
    {
        $str = preg_replace("#".$key."#i", $val, $str);
    }

    if ($lowercase === TRUE)
    {
        $str = strtolower($str);
    }

    return trim($str, $separator);
}

function send_email($to, $subject, $mailbody)
{

    require base_path("vendor/autoload.php");
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "hopniger@gmail.com";
        $mail->Password = "inwszdmvtmzqtzii";
        $mail->SMTPSecure = "STARTTLS";
        $mail->Port = 587;
        $mail->setFrom("hopniger@gmail.com","HOP");
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $mailbody;
        // $mail->SMTPOptions = array(
        //     'ssl' => array(
        //         'verify_peer' => false,
        //         'verify_peer_name' => false,
        //         'allow_self_signed' => true
        //     )
        // );
        if (!$mail->send()) {
            // dd($e->getMessage());
            return 0;
        } else {
            return 1;
        }
    } catch (Exception $e) {
         dd($e->getMessage());
        return 0;
    }
}
// function send_normal_SMS($message, $mobile_numbers, $sender_id = "")
// {
//     $username = "teyaar"; //username
//     $password = "06046347"; //password
//     $sender_id = "smscntry";
//     $message_type = "N";
//     $delivery_report = "Y";
//     $url = "http://www.smscountry.com/SMSCwebservice_Bulk.aspx";
//     $proxy_ip = "";
//     $proxy_port = "";
//     $message_type = "N";
//     $message = urlencode($message);
//     $sender_id = (!empty($sender_id)) ? $sender_id : $sender_id;
//     $ch = curl_init();
//     if (!$ch) {
//         $curl_error = "Couldn't initialize a cURL handle";
//         return false;
//     }
//     $ret = curl_setopt($ch, CURLOPT_URL, $url);
//     curl_setopt($ch, CURLOPT_POST, 1);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, "User=" . $username . "&passwd=" . $password . "&mobilenumber=" . $mobile_numbers . "&message=" . $message . "&sid=" . $sender_id . "&mtype=" . $message_type . "&DR=" . $delivery_report);
//     $ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//     if (!empty($proxy_ip)) {
//         $ret = curl_setopt($ch, CURLOPT_PROXY, $proxy_ip . ":" . $proxy_port);
//     }
//     $curl_response = curl_exec($ch);
//     if (curl_errno($ch)) {
//         $curl_error = curl_error($ch);
//     }
//     if (empty($ret)) {
//         curl_close($ch);
//         // dd('1');
//         return false;
//     } else {
//         $curl_info = curl_getinfo($ch);
//         curl_close($ch);
//         return true;
//     }
// }

function send_normal_SMS($message, $receiverNumber, $sender_id = "")
{
    try {
        $receiverNumber = '+'.str_replace("+","",$receiverNumber);
        $account_sid = getenv("TWILIO_SID");
        $auth_token = getenv("TWILIO_TOKEN");
        $twilio_number = getenv("TWILIO_FROM");
        $client = new Client($account_sid, $auth_token);
        $client->messages->create($receiverNumber, [
            'from' => $twilio_number, 
            'body' => $message]
        );
        return 1;
    } catch (TwilioException $e) {
        return $e->getMessage();
        // return 0;
    }
}

function convert_all_elements_to_string($data=null){
    if($data != null){
        array_walk_recursive($data, function (&$value, $key) {
            if (! is_object($value) ) {
                if($value){
                    $value = (string) $value;
                }else{
                    $value = (string) $value;
                }
            } else {
                $json = json_encode($value);
                $array = json_decode($json, true);

                array_walk_recursive($array, function (&$obj_val, $obj_key) {
                    $obj_val = (string) $obj_val;
                });

                if (! empty($array) ) {
                    $json = json_encode($array);
                    $value = json_decode($json);
                } else {
                    $value = [];
                }
            }
        });
    }
    return $data;
}
function thousandsCurrencyFormat($num) {

    if( $num > 1000 ) {
        $x = round($num);
        $x_number_format = number_format($x);
        $x_array = explode(',', $x_number_format);
        $x_parts = array('k', 'm', 'b', 't');
        $x_count_parts = count($x_array) - 1;
        $x_display = $x;
        $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
        $x_display .= $x_parts[$x_count_parts - 1];
        return $x_display;
    }

    return $num;
}
function order_status($id)
   {
        $status_string = "Pending";
        if($id == config('global.order_status_pending'))
                {
                   $status_string = trans('custom.pending');
                }
                if($id == config('global.order_status_accepted'))
                {
                   $status_string = trans('custom.order_accepted');//"Order Placed";
                }
                if($id == config('global.order_status_ready_for_delivery'))
                {
                   $status_string = trans('custom.ready_for_delivery');//"Ready for Delivery";
                }
                if($id == config('global.order_status_dispatched'))
                {
                   $status_string = trans('custom.dispatched');//"Dispatched";
                }
                if($id == config('global.order_status_delivered'))
                {
                   $status_string = trans('custom.delivered');//"Delivered";
                }
                if($id == config('global.order_status_cancelled'))
                {
                   $status_string = trans('custom.cancelled');//"Cancelled";
                }
                if($id == config('global.order_status_returned'))
                {
                   $status_string = trans('custom.returned');//"Returned";
                }
    return $status_string;
   }
   function seller_order_status($id)
   {
        $status_string = "Pending";
        if($id == config('global.order_status_pending'))
                {
                   $status_string = "Waiting For Confirmation";
                }
                if($id == config('global.order_status_accepted'))
                {
                   $status_string = "Payment Pending";
                }
                if($id == config('global.order_payment_completed'))
                {
                   $status_string = "Payment Completed";
                }
                if($id == config('global.order_status_ready_for_delivery'))
                {
                   $status_string = "Driver Waiting";
                }
                
                if($id == config('global.order_status_driver_accepted'))
                {
                   $status_string = "Driver Accepted";
                }
                if($id == config('global.order_status_dispatched'))
                {
                   $status_string = "On the way";
                }
                if($id == config('global.order_status_delivered'))
                {
                   $status_string = "Order Delivered";
                }
                if($id == config('global.order_status_cancelled'))
                {
                   $status_string = "Cancelled";
                }
                if($id == config('global.order_status_returned'))
                {
                   $status_string = "Returned";
                }
                if($id == config('global.order_status_rejected'))
                {
                   $status_string = "Rejected";
                }
                if($id == config('global.order_payment_completed'))
                {
                   $status_string = "Payment Completed";
                }
    return $status_string;
   }

   function service_status($id)
   {
        $status_string = "Pending";

        if($id == config('global.service_quote_sent'))
        {
            $status_string = "Quote Sent";
        }
        if($id == config('global.service_status_pending'))
        {
            $status_string = "Pending";
        }
        if($id == config('global.service_status_rejected'))
        {
            $status_string = "Request Rejected";
        }

        if($id == config('global.service_quote_accepted'))
        {
            $status_string = "Quote Accepted";
        }

        if($id == config('global.service_quote_cancelled'))
        {
            $status_string = "Quote Cancelled";
        }


        // dd($status_string);
        
        
        // if($id == config('global.service_quote_added'))
        // {
        //     $status_string = "Request Confirmed";
        // }
        
        
        // if($id == config('global.service_quote_rejected'))
        // {
        //     $status_string = "Offer Rejected";
        // }
        // if($id == config('global.service_location_added'))
        // {
        //     $status_string = "Waiting For Service Provider";
        // }
        // if($id == config('global.service_on_the_way'))
        // {
        //     $status_string = "On the way to site";
        // }
        // if($id == config('global.service_work_started'))
        // {
        //     $status_string = "Work On Progress";
        // }
        // if($id == config('global.service_work_completed'))
        // {
        //     $status_string = "Work Finished";
        // }
        // if($id == config('global.service_payment_completed'))
        // {
        //     $status_string = "Work Finished";
        // }
        // if($id == config('global.service_service_completed'))
        // {
        //     $status_string = "Completed";
        // }
        return $status_string;
   }

   function service_store_status($id)
   {
        $status_string = "Pending";
        if($id == config('global.service_status_pending'))
        {
            $status_string = "Waiting For Confirmation";
        }
        if($id == config('global.service_status_rejected'))
        {
            $status_string = "Request Rejected";
        }
        if($id == config('global.service_quote_added'))
        {
            $status_string = "Waiting for approval";
        }
        
        if($id == config('global.service_quote_rejected'))
        {
            $status_string = "Offer Rejected";
        }
        if($id == config('global.service_service_completed'))
        {
            $status_string = "Completed";
        }
        return $status_string;
   }

   function report_user_problem($id)
   {
        $problems = config('global.report_user_problems');
        return isset($problems[$id]) ? $problems[$id] : '';
   }
   function process_order($list, $vendor_id = '',$store_id='',$store_vendor_id='')
   {
            foreach($list as $key=>$value)
            {
                if($value->status == config('global.order_status_pending'))
                {
                   $list[$key]->status_string = trans('order.pending');
                }
                if($value->status == config('global.order_status_accepted'))
                {
                   $list[$key]->status_string = trans('order.accepted');
                }
                if($value->status == config('global.order_status_ready_for_delivery'))
                {
                   $list[$key]->status_string = trans('order.ready_for_delivery');
                }
                if($value->status == config('global.order_status_dispatched'))
                {
                   $list[$key]->status_string = trans('order.dispatched');
                }
                if($value->status == config('global.order_status_delivered'))
                {
                   $list[$key]->status_string = trans('order.delivered');
                }
                if($value->status == config('global.order_status_cancelled'))
                {
                   $list[$key]->status_string = trans('order.cancelled');
                }


               if(!empty($value->address_id))
               {
               $list[$key]->shipping_address = App\Models\UserAdress::get_address_details($value->address_id);
               }
               
               $order_products  = \App\Models\OrderProductsModel::select('product_name', 'order_products.*', 'u1.name as vendor')->join('product', 'product.id', 'order_products.product_id')->leftjoin('users as u1', 'u1.id', 'order_products.vendor_id')->where(['order_id'=>$value->order_id]);
               if ($store_id && $store_vendor_id) {
                $order_products = $order_products->where('store_id',$store_id)->where('order_products.vendor_id', $store_vendor_id);
               } 
               if($vendor_id && !$store_id && !$store_vendor_id){
                $order_products = $order_products->where('order_products.vendor_id', $vendor_id);
               }
               $order_products = $order_products->get();
               $op_total = 0;
               foreach ($order_products as $okey => $oval) {
                $op_total+=$oval->grand_total;
                    $product_image = '';
                    if ($oval->product_attribute_id) {
                        $det = DB::table('product_selected_attribute_list')->select('image')->where('product_id', $oval->product_id)->where('product_attribute_id', $oval->product_attribute_id)->first();
                        if ($det) {
                            $images = $det->image;
                            $images = explode(",", $det->image);
                            $images = array_values(array_filter($images));
                            $product_image = (count($images) > 0) ? $images[0] : $det->image;
                        }
                    } else {
                        $det = DB::table('product_selected_attribute_list')->select('image')->where('product_id', $oval->product_id)->orderBy('product_attribute_id', 'DESC')->limit(1)->first();
                        if ($det) {
                            $images = $det->image;
                            $images = explode(",", $det->image);
                            $images = array_values(array_filter($images));
                            $product_image = (count($images) > 0) ? $images[0] : $det->image;
                        }
                    }
                    $order_products[$okey]->prod_image = $product_image ? url(config('global.upload_path') . '/' . config('global.product_image_upload_dir') . $product_image) : '';
                }
                $list[$key]->op_total = $op_total;
                $list[$key]->order_products = $order_products;

            //    $order_products  = App\Models\OrderProductsModel::product_details(['order_id'=>$value->order_id]);
            //    $list[$key]->order_products = process_product_data($order_products);

           }
           return $list;
    }
    function process_product_data($row, $lang_code = "1")
   {

      $ratings  = [];
      $product_row_data = $row;
      $sl = 0;
      foreach($row as $item) {

      if($lang_code == 2)
      {
      $product_row_data[$sl]->product_name      = (string) $item->product_name_arabic;
      $product_row_data[$sl]->product_desc_full      = (string) $item->product_desc_full_arabic;
      $product_row_data[$sl]->product_desc_short      = (string) $item->product_desc_short_arabic;
      }
      $product_images = [];

      if(!empty($item->image))
      {
         $imagesarray = explode(",",$item->image);
         foreach($imagesarray as $img)
         {
           $product_images[] = (string) url(config('global.upload_path').config('global.product_image_upload_dir').$img);
         }
      }
      else
      {
          $product_images[] = (string) url(config('global.upload_path').'placeholder.jpg');
      }
      if(isset($item->order_status))
      {
      $product_row_data[$sl]->status_string = order_status($item->order_status);
      }

      $stock_status = 0;
      $stock_status_string = "Out of stock";
      if(isset($item->stock_quantity))
      {
      if($item->stock_quantity > 0)
      {
        $stock_status = 1;
        $stock_status_string = "In stock";
      }
      }


      $discountper = 0;
      if(isset($item->sale_price) && isset($item->regular_price))
      {
          $diff = $item->regular_price - $item->sale_price;
          $discountper = ($diff/$item->regular_price)*100;
          $discountper = round($discountper);
      }
      $product_row_data[$sl]->stock_status        = $stock_status;
      $product_row_data[$sl]->stock_status_string = $stock_status_string;
      $product_row_data[$sl]->product_images      = $product_images;
      $product_row_data[$sl]->discount_per        = $discountper;
      $product_row_data[$sl]->share_url           = url('share/product/'.encryptor($item->id));





      $sl++;
   }
   $product_row_data = process_vendor($product_row_data);
   return $product_row_data;

   }
   function encryptor($string) {
    $output = false;

    $encrypt_method = "AES-128-CBC";
    //pls set your unique hashing key
    $secret_key = 'muni';
    $secret_iv = 'muni123';

    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    //do the encyption given text/string/number

        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);


    return $output;
}

function decryptor( $string) {
    $output = false;

    $encrypt_method = "AES-128-CBC";
    //pls set your unique hashing key
    $secret_key = 'muni';
    $secret_iv = 'muni123';

    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);


        //decrypt the given text/string/number
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);


    return $output;
}
 function process_vendor($row, $lang_code = "1")
   {
      foreach($row as $key=>$item) {

        $row[$key]->vendor_img_path              = (string) image_link("placeholder.png",config('global.user_image_upload_dir'));
      if(!empty($item->vendorimage))
      {
        $row[$key]->vendor_img_path             = (string) url(config('global.upload_path').config('global.user_image_upload_dir').$item->vendorimage);
      }

      }
      return $row;
   }
    function image_link($image ,$directory )
    {
    if($image !="") {
        return url(config('global.upload_path').$directory.$image);

    }
   }

   function process_product_data_api($row)
{
    // printr($row);
    $product_row_data = [];
    $product_row_data["product_id"]         = (string) $row->product_id;
    if (isset($row->product_attribute_id)) {
        $product_row_data["product_variant_id"] = (string) $row->product_attribute_id;
    }
    $product_row_data["product_name"]       = (string) $row->product_name;
    $product_row_data["product_desc"]       = (string) $row->product_full_descr;
    $product_row_data["stock_quantity"]       =  $row->stock_quantity;
    $product_row_data["sku"]       = $row->pr_code;
    $product_row_data["weight"]       = $row->weight;
    $product_row_data["length"]       = $row->length;
    $product_row_data["height"]       = $row->height;
    $product_row_data["width"]       = $row->width;
    if ( isset($row->product_vender_id) ) {
        $product_row_data["product_vender_id"]  = (string) $row->product_vender_id;
    }

    if (isset($row->category_id)) {
        $product_row_data["category_id"] = (string) $row->category_id;
    }
    if (isset($row->category_name)) {
        $product_row_data["category_name"] = (string) $row->category_name;
    }
    if (isset($row->brand) && ($row->brand > 0) ) {
        $product_row_data["product_brand_id"] = (string) $row->brand;
    } else {
        $product_row_data["product_brand_id"] = '';
    }

    if (isset($row->brand_name)) {
        $product_row_data["brand_name"] = (string) $row->brand_name;
    } else {
        $product_row_data["brand_name"] = '';
    }

    $product_row_data["product_type"]       = (string) $row->product_type;
    $product_images = explode(",", $row->image);
    $product_images = array_values(array_filter($product_images));
    $product_image  = (count($product_images) > 0) ? $product_images[0] : $row->image;
    $product_row_data["product_image"] = get_uploaded_image_url( $product_image, 'product_image_upload_dir', 'placeholder.png' );//url(config('global.upload_path') . '/' . config('global.product_image_upload_dir') . str_replace(' ', '%20', $product_image));

    $product_row_data["product_images"]     = array();
    if (is_array($product_images)) {
        foreach($product_images as $key=>$image) {
            $product_row_data["product_images"][] = get_uploaded_image_url( $image, 'product_image_upload_dir', 'placeholder.png' );//$image;//url(config('global.upload_path') . '/' . config('global.product_image_upload_dir') . str_replace(' ', '%20', $image));
        }
    }
    // $product_row_data["rated_users"] = (!empty($row->rated_users)) ? (string) $row->rated_users : "0";
    // $product_row_data["rating"]      = (!empty($row->rated_users)) ? (string) $row->rating: "0";
    $product_row_data["sale_price"]         = number_format((float) $row->sale_price,2,".", "");
    $product_row_data["regular_price"]      = number_format((float) $row->regular_price,2,".", "");
    $product_row_data["stock_quantity"]      = $row->stock_quantity;
    $product_row_data["store_id"]          = $row->store_id;
    $product_row_data["store_name"]          = $row->store_name;
    $product_row_data["store_avg_rating"]          = 0;
    $product_row_data["store_total_reviews"]          = 0;

    // if ($row->size_chart) {
    //     $product_row_data["size_chart"] = asset($row->size_chart);
    // }else{
    //     $product_row_data["size_chart"] = '';
    // }

    if ( $product_row_data["sale_price"] < $product_row_data["regular_price"] ) {
        $product_row_data['offer_enabled'] = 1;
        $price_diff = $product_row_data["regular_price"] - $product_row_data["sale_price"];
        $offer_percentage = ($price_diff / $product_row_data["regular_price"]) * 100;
        $offer_percentage = ceil($offer_percentage);
        $product_row_data['offer_percentage'] = $offer_percentage;
    } else {
        $product_row_data['offer_enabled'] = 0;
        $product_row_data['offer_percentage'] = 0;
    }
    
    return $product_row_data;
}

function generate_otp($count){
    if($count == 3){
        return 111;
    }
  return 1111;
  //return rand(1111,9999);
}
function wallet_history($data=[])
    {
        $data = (object)$data;
        $WalletHistory = new \App\Models\WalletHistory();
        $WalletHistory->user_id	        = $data->user_id;
        $WalletHistory->wallet_amount	= $data->wallet_amount;
        $WalletHistory->pay_type	    = $data->pay_type;
        $WalletHistory->description	    = $data->description;
        $WalletHistory->pay_method	    = isset($data->pay_method) ? $data->pay_method : 0;
        $WalletHistory->created_at	    = gmdate('Y-m-d H:i:00');
        $WalletHistory->updated_at	    = gmdate('Y-m-d H:i:00');

        if($WalletHistory->save())
            return 1;

        return 0;
    }
    if (! function_exists('web_date_in_timezone') ) {
        function web_date_in_timezone($date, $format="d M Y h:i A",$server_time_zone="Etc/GMT")
        {
            $timezone = session('user_timezone');
            if(!$timezone){
                $timezone = $server_time_zone;
            }
            $timezone_server    = new DateTimeZone($server_time_zone);
            $timezone_user      = new DateTimeZone($timezone);
            $dt = new DateTime($date, $timezone_server);
            $dt->setTimezone($timezone_user);
            return $dt->format($format);
        }
    }

    if (! function_exists('api_date_in_timezone') ) {
        function api_date_in_timezone($date, $format,$timezone,$server_time_zone="Etc/GMT")
        {
            if(empty( $format)) $format="d M Y h:i A";
            $timezone_server    = new DateTimeZone($server_time_zone);
            $timezone_user      = new DateTimeZone($timezone);
            $dt = new DateTime($date, $timezone_server);
            $dt->setTimezone($timezone_user);
            return $dt->format($format);
        }
    }

    function removeNamespaceFromXML( $xml )
{
    // Because I know all of the the namespaces that will possibly appear in
    // in the XML string I can just hard code them and check for
    // them to remove them
    $toRemove = ['rap', 'turss', 'crim', 'cred', 'j', 'rap-code', 'evic'];
    // This is part of a regex I will use to remove the namespace declaration from string
    $nameSpaceDefRegEx = '(\S+)=["\']?((?:.(?!["\']?\s+(?:\S+)=|[>"\']))+.)["\']?';

    // Cycle through each namespace and remove it from the XML string
   foreach( $toRemove as $remove ) {
        // First remove the namespace from the opening of the tag
        $xml = str_replace('<' . $remove . ':', '<', $xml);
        // Now remove the namespace from the closing of the tag
        $xml = str_replace('</' . $remove . ':', '</', $xml);
        // This XML uses the name space with CommentText, so remove that too
        $xml = str_replace($remove . ':commentText', 'commentText', $xml);
        // Complete the pattern for RegEx to remove this namespace declaration
        $pattern = "/xmlns:{$remove}{$nameSpaceDefRegEx}/";
        // Remove the actual namespace declaration using the Pattern
        $xml = preg_replace($pattern, '', $xml, 1);
    }

    // Return sanitized and cleaned up XML with no namespaces
    return $xml;
}

function namespacedXMLToArray($xml)
{
    // One function to both clean the XML string and return an array
    return json_decode(json_encode(simplexml_load_string(removeNamespaceFromXML($xml))), true);
}
function check_permission($module,$permission){
    $userid = Auth::user()->id;
    $privilege = 0;
    if ($userid > 1) {
        $privileges = \App\Models\UserPrivileges::privilege();
        $privileges = json_decode($privileges, true);
        if (!empty($privileges[$module][$permission])) {
            if ($privileges[$module][$permission] == 1) {
                $privilege = 1;
            }
        }
    } else {
        $privilege = 1;
    }
    return $privilege;
}
function retrive_hash_tags($data=''){
    $d = explode(" ",$data);
    $words=[];
    foreach($d as $k){
        if(substr($k,0,1) == '#'){
          $words[]=ltrim($k,'#');
        }

    }
    return $words;
}
function GetDrivingDistance($lat1, $lat2, $long1, $long2)
    {
        $dist = '-';
        $time = '-';
        $km=$tm=0;
        if( $lat1 != '' && $lat2 != '' && $long1 != '' && $long2 != ''){
            $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&key=AIzaSyCtugJ9XvE2MvkXCBeynQDFKq-XN_5xsxM";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($ch);
            curl_close($ch);
            $response_a = json_decode($response, true);
            
            if(isset($response_a['rows'][0]['elements'][0]['distance']['text'])){
                $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
                $time = $response_a['rows'][0]['elements'][0]['duration']['text'];
                $km = $response_a['rows'][0]['elements'][0]['distance']['value'];
                $tm = $response_a['rows'][0]['elements'][0]['duration']['value'];
            }
        }
        return array('distance' => $dist, 'time' => $time,'km'=>$km,'tm'=>$tm);
    }
    function GetDrivingDistanceToMultipleLocations($from_latlong, $destinations)
    {
        $distance_data = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?origins='.$from_latlong.'&destinations='.$destinations.'&mode=driving&key=AIzaSyCtugJ9XvE2MvkXCBeynQDFKq-XN_5xsxM');
        return json_decode($distance_data, true);
    }
    if (!function_exists('validateAccesToken')) {
    function validateAccesToken($access_token)
    {
        $user = App\Models\User::where(['user_access_token' => $access_token])->get();

        if ($user->count() == 0) {
            http_response_code(401);
            echo json_encode([
                'status' => "0",
                'message' => 'Invalid login',
                'oData' => (object)[],
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
                    'oData' => (object)[],
                    'errors' => (object) [],
                ]);
                exit;
            }
        }
    }
    function adminNotification($related_id, $related_to, $title, $msg)
    {
         $dData =  [
            'notifiable_id' => $related_id,
            'related_to' => $related_to,
            'title' => $title,
            'message' => $msg,
            'type'=>$related_to
        ];
        $data  = [
            'related_id' => $related_id,
            'related_to' => $related_to,
            'title' => $title,
            'message' => $msg,
        ];
        $dData['data'] = json_encode($data);
        $obj = new \App\Models\DbNotification();
        $obj->create($dData);

   }
}
?>
