<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Contract\Database;
use App\Models\OrderModel;
use App\Models\User;

class SendOrderNottificationDriver extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_status_nottification:driver {order_id}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Database $database)
     {
         parent::__construct();
         $this->database = $database;
     }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $order_id = $this->argument('order_id');
        $order = OrderModel::with(['products','customer','store'])->where('order_id', $order_id)->get();
        if ( $order->count() > 0 ) {
            $order = $order->first();

            $notification_id = time();
            
            $ntype = '';

            if($order->status == config('global.order_status_ready_for_delivery')){
                $title = 'New Order';
                $description = "You have a new order #".$order->invoice_id." ";
                $ntype = 'driver_new_order';
                if( $order->request_deligate != OWN_DELIGATE_ID ){
                    $driver_list = User::where(['user_type_id'=>DRIVER_USER_TYPE_ID])->join('deligates','deligates.id','=',$order->request_deligate)->get();
                    $notification_data=[];
                    $fcm_tokens=[];
                    foreach( $driver_list as $driver ){
                        if (!empty($driver->firebase_user_key)) {
                            $notification_data["Nottifications/" . $driver->firebase_user_key . "/" . $notification_id] = [
                                "title" => $title,
                                "description" => $description,
                                "notificationType" => $ntype,
                                "createdAt" => gmdate("d-m-Y H:i:s", $notification_id),
                                "orderId" => (string) $order_id,
                                "invoiceId" => (string) $order->invoice_id,
                                "url" => "",
                                "imageURL" => '',
                                "read" => "0",
                                "seen" => "0",
                            ];
                            
                        }
        
                        if (!empty($driver->user_device_token)) {
                            $fcm_tokens[]=$driver->user_device_token;
                            // send_single_notification($driver->user_device_token, [
                            //     "title" => $title,
                            //     "body" => $description,
                            //     "icon" => 'myicon',
                            //     "sound" => 'default',
                            //     "click_action" => "EcomNotification"],
                            //     ["type" => $ntype,
                            //         "notificationID" => $notification_id,
                            //         "orderId" => (string) $order_id,
                            //         "invoiceId" => (string) $order->invoice_id,
                            //         "imageURL" => "",
                            //     ]);
                        }
                    }
                    if($notification_data){
                        $this->database->getReference()->update($notification_data);
                    }
                    if(!empty($fcm_tokens)){
                        $res = send_multicast_notification($fcm_tokens,
                        [
                            "title" => $title,
                            "body" => $description,
                            "icon" => 'myicon',
                            "sound" => 'default',
                            "click_action" => "EcomNotification"],
                            ["type" => $ntype,
                                "notificationID" => $notification_id,
                                "orderId" => (string) $order_id,
                                "invoiceId" => (string) $order->invoice_id,
                                "imageURL" => "",
                            ]);
                      }
                }


            }else if($order->status == config('global.order_payment_completed')){
                $title = 'Order Payment Completed';
                $description = "Your order #".$order->invoice_id." payment has been completed by the customer.";
                $ntype = 'order_payment_completed';
            }else if($order->status == config('global.order_status_delivered')){
                $title = 'Order Delivered';
                $description = "Your order #".$order->invoice_id." delivered";
                $ntype = 'store_order_delivered';
            }else if($order->status == config('global.order_status_cancelled')){
                $title = 'Order Cancelled';
                $description = "Your order #".$order->invoice_id." cancelled";
                $ntype = 'store_order_cancelled';
            }else if($order->status == config('global.order_status_returned')){
                $title = 'Order Returned';
                $description = "Your order #".$order->invoice_id." returned";
                $ntype = 'store_order_returned';
            }else if($order->status == config('global.order_status_rejected')){
                $ntype = '';
            }
            
            
            if($ntype != 'driver_new_order'){
                $customer = $order->store;
                
                if (!empty($customer->firebase_user_key)) {
                    $notification_data["Nottifications/" . $customer->firebase_user_key . "/" . $notification_id] = [
                        "title" => $title,
                        "description" => $description,
                        "notificationType" => $ntype,
                        "createdAt" => gmdate("d-m-Y H:i:s", $notification_id),
                        "orderId" => (string) $order_id,
                        "invoiceId" => (string) $order->invoice_id,
                        "url" => "",
                        "imageURL" => '',
                        "read" => "0",
                        "seen" => "0",
                    ];
                    $this->database->getReference()->update($notification_data);
                }

                if (!empty($customer->user_device_token)) {
                    send_single_notification($customer->user_device_token, [
                        "title" => $title,
                        "body" => $description,
                        "icon" => 'myicon',
                        "sound" => 'default',
                        "click_action" => "EcomNotification"],
                        ["type" => $ntype,
                            "notificationID" => $notification_id,
                            "orderId" => (string) $order_id,
                            "invoiceId" => (string) $order->invoice_id,
                            "imageURL" => "",
                        ]);
                }
            }
        }
        return 0;
    }
}
