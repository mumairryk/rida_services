<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\OrderModel;
use App\Models\OrderProductsModel;
use DB;

class SendOrderEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:send_order_email {--uri=} {--uri2=} {--uri3=} {--uri4=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Order Email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $subject = 'Your order has been received';
        $to =  urldecode($this->option("uri"));
        $order_id = $this->option("uri2");
        $name = urldecode($this->option("uri3"));
        $user_id = $this->option("uri4");

        $order = OrderModel::with(['products' => function ($qr) {
            $qr->select('order_products.*', 'product_attribute_id as product_variant_id', 'default_attribute_id','product_name')->join('product', 'product.id', 'order_products.product_id');
        }])->where('user_id', $user_id)->where('order_id', $order_id)->first();

        if ($order) {
            $lowest_order_prd_status = OrderProductsModel::where('order_id',$order_id)->orderby('order_status','asc')->first();
            if(isset($lowest_order_prd_status->order_status)){
                $order->status = $lowest_order_prd_status->order_status;
                $order->status_text = order_status($lowest_order_prd_status->order_status);
            }else{
                $order->status_text = order_status($order->status);
            }
            $order->address = \App\Models\UserAdress::get_address_details($order->address_id);
            $products = $order->products;
            foreach ($products as $pkey => $pval) {
                $products[$pkey]->order_status_text = order_status($pval->order_status);
                $product_image = '';
                if ($pval->default_attribute_id) {
                    $det = DB::table('product_selected_attribute_list')->select('image')->where('product_id', $pval->product_id)->where('product_attribute_id', $pval->default_attribute_id)->first();
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

            }
            $order->products = $products;
        }
        $o_data = $order;
        $mailbody = view('emai_templates.order_success', compact('order', 'name'));
        send_email($to, $subject, $mailbody);
    }
}
