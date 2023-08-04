<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Contract\Database;
use App\Models\OrderModel;

class OrderStatusNottification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_status_nottification:order {order_id}';

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

            $title = 'Order Accepted';
            $notification_id = time();
            $description = "Your order #".$order->invoice_id." has been accepted by the seller now make payment for further";
            $ntype = 'order_accepted';

            if($order->status == config('global.order_status_ready_for_delivery')){
                $title = 'Order Ready For Delivery';
                $description = "Your order #".$order->invoice_id." ready for delivery";
                $ntype = 'order_ready_for_delivery';
            }else if($order->status == config('global.order_status_dispatched')){
                $title = 'Order Dispatched';
                $description = "Your order #".$order->invoice_id." dispatched";
                $ntype = 'order_dispatched';
            }else if($order->status == config('global.order_status_delivered')){
                $title = 'Order Delivered';
                $description = "Your order #".$order->invoice_id." delivered";
                $ntype = 'order_delivered';
            }else if($order->status == config('global.order_status_cancelled')){
                $title = 'Order Cancelled';
                $description = "Your order #".$order->invoice_id." cancelled";
                $ntype = 'order_cancelled';
            }else if($order->status == config('global.order_status_returned')){
                $title = 'Order Returned';
                $description = "Your order #".$order->invoice_id." returned";
                $ntype = 'order_returned';
            }else if($order->status == config('global.order_status_rejected')){
                $title = 'Order Rejected';
                $description = "Your order #".$order->invoice_id." rejected by the seller";
                $ntype = 'order_rejected';
            }else if($order->status == config('global.order_payment_completed')){
                $title = 'Order Payment Completed';
                $description = "Your order #".$order->invoice_id." payment completed";
                $ntype = 'order_payment_completed';
            }
            
            
            
            $customer = $order->customer;
            
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
        return 0;
    }
}
