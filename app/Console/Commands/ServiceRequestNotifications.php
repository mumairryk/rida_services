<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Contract\Database;
use App\Models\ServiceRequest;
use App\Models\User;

class ServiceRequestNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'service_request:seller {service_id}';
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
        $service_id = $this->argument('service_id');
        $order = ServiceRequest::with(['serviceRequestImages','user','store'])->where('id', $service_id)->get();
        if ( $order->count() > 0 ) {
            $order = $order->first();

            $title = 'New Service Request';
            $notification_id = time();
            $description = "You have a new service request #".$order->service_invoice_id." ";
            $ntype = '';

            
            if($order->status == config('global.service_status_pending'))
            {
                $title = 'New Service Request';
                $description = "You have a new service request #".$order->service_invoice_id." ";
                $ntype = 'new_service_request';
            }
            if($order->status == config('global.service_status_rejected'))
            {
                $title = 'Service Request Rejected';
                $description = "You have rejected the request for invoice #".$order->service_invoice_id." ";
                $ntype = 'service_request_rejected';
            }
            if($order->status == config('global.service_quote_added'))
            {
                $title = 'Quote added';
                $description = "Quote added for invoice #".$order->service_invoice_id." ";
                $ntype = 'quote_added';
            }
            if($order->status == config('global.service_quote_accepted'))
            {
                $title = 'Quote Accepted';
                $description = "Quote accepted by the customer for invoice #".$order->service_invoice_id." ";
                $ntype = 'quote_accepted';
            }
            
            if($order->status == config('global.service_quote_rejected'))
            {
                $title = 'Quote Rejected';
                $description = "Quote rejected by the customer for invoice #".$order->service_invoice_id." ";
                $ntype = 'quote_rejected';
            }
            if($order->status == config('global.service_location_added'))
            {
                $title = 'Location Updated';
                $description = "Location Updated by the customer for invoice #".$order->service_invoice_id." ";
                $ntype = 'location_updated';
            }
            if($order->status == config('global.service_on_the_way'))
            {
                $title = 'On the way to site';
                $description = "On the way to site for invoice #".$order->service_invoice_id." ";
                $ntype = 'on_the_way';
            }
            if($order->status == config('global.service_work_started'))
            {
                $title = 'Work Started';
                $description = "Work Started for invoice #".$order->service_invoice_id." ";
                $ntype = 'work_started';
            }
            if($order->status == config('global.service_work_completed'))
            {
                $title = 'Work Completed';
                $description = "Work completed for invoice #".$order->service_invoice_id." ";
                $ntype = 'work_completed';
            }
            if($order->status == config('global.service_payment_completed'))
            {
                $title = 'Payment Completed';
                $description = "Payment Completed by the customer for invoice #".$order->service_invoice_id." ";
                $ntype = 'payment_completed';
            }
            if($order->status == config('global.service_service_completed'))
            {
                $title = 'Work Finished';
                $description = "Work Finished for invoice #".$order->service_invoice_id." ";
                $ntype = 'work_finished';
            }
            
            
            if($ntype != ''){
                $customer = $order->store;
                
                if (!empty($customer->firebase_user_key)) {
                    $notification_data["Nottifications/" . $customer->firebase_user_key . "/" . $notification_id] = [
                        "title" => $title,
                        "description" => $description,
                        "notificationType" => $ntype,
                        "createdAt" => gmdate("d-m-Y H:i:s", $notification_id),
                        "orderId" => (string) $service_id,
                        "invoiceId" => (string) $order->service_invoice_id,
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
                            "orderId" => (string) $service_id,
                            "invoiceId" => (string) $order->service_invoice_id,
                            "imageURL" => "",
                        ]);
                }
            }

            
        }
        return 0;
    }
}
