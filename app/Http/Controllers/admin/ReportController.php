<?php

namespace App\Http\Controllers\admin;

use App\Exports\ExportReports;
use App\Http\Controllers\Controller;
use App\Models\OrderModel;
use App\Models\OrderProductsModel;
use App\Models\User;
use App\Models\WholesaleOrder;
use App\Models\Quotation;
use App\Models\Services;
use App\Models\BankModel;
use App\Models\ServiceQuotes;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class ReportController extends Controller
{
    public function orders(Request $request)
    {

        if (!check_permission('orders', 'View')) {
            abort(404);
        }
        $page_heading = "Orders Report";
        $order_id = $_GET['order_id'] ?? '';
        $name = $_GET['name'] ?? '';
        $customer = $_GET['customer'] ?? '';
        $from = !empty($_GET['from']) ? date('Y-m-d', strtotime($_GET['from'])) : '';
        $to = !empty($_GET['to']) ? date('Y-m-d', strtotime($_GET['to'])) : '';

        $list = OrderModel::select('orders.*', 'users.name', DB::raw("CONCAT(users.first_name,' ',users.last_name) as customer_name"))->leftjoin('users', 'users.id', 'orders.user_id')->with(['customer' => function ($q) use ($name) {
            $q->where('display_name', 'like', '%' . $name . '%');
        }]);
        if ($name) {
            $list = $list->whereRaw("concat(first_name, ' ', last_name) like '%" . $name . "%' ");
        }
        if ($order_id) {
            $list = $list->where('orders.invoice_id', $order_id);
        }
        if ($customer) {
            $list = $list->where('orders.user_id', $customer);
        }
        if ($from) {
            $list = $list->whereDate('orders.created_at', '>=', $from . ' 00:00:00');
        }
        if ($to) {
            $list = $list->where('orders.created_at', '<=', $to . ' 23:59:59');
        }
        $list = $list->orderBy('orders.order_id', 'DESC');

        
        if ($request->excel != 'Export') {
            $list = $list->paginate(10);
            return view('admin.reports.orders', compact('page_heading', 'list', 'order_id', 'name', 'from', 'to'));
        } else {
            $list = $list->get();
            $rows = array();
            $i = 1;
            foreach ($list as $key => $item) {
                
                $rows[$key]['i'] = $i;
                $rows[$key]['Order No'] = config('global.sale_order_prefix').date(date('Ymd', strtotime($item->created_at))).$item->order_id;;
                $rows[$key]['invoice_id'] = $item->invoice_id;
                $rows[$key]['customer_name'] = $item->name??$item->customer_name;
                $rows[$key]['grand_total'] = $item->grand_total;
                $rows[$key]['payment_mode'] = payment_mode($item->payment_mode);
                $rows[$key]['created_date'] = web_date_in_timezone($item->created_at, 'd-M-Y h:i A');
                

                $i++;
            }
            $headings = [
                "#",
                "Order No",
                "Invoice ID",
                "Customer",
                "Total",
                "Payment Mode",
                "Order Date",
            ];
            $coll = new ExportReports([$rows], $headings);
            $ex = Excel::download($coll, 'vendors_' . date('d_m_Y_h_i_s') . '.xlsx');
            if (ob_get_length()) {
                ob_end_clean();
            }

            return $ex;
        }
    }
    public function service_quotes(Request $request)
    {
        if (!check_permission('service_quotes', 'View') && \Auth::user()->role != 5) {
            abort(404);
        }

        $service = $_GET['service'] ?? '';
        $service_name = Services::find($service);
        $service_name = $service_name->name??'service_quotes';
        $page_heading = $service == 5 ? 'Service Bookings Reports - '.$service_name :"Service Quotes Reports - ".$service_name;
        $datamain = ServiceQuotes::select('service_quotes.*','users.name as customer','users.dial_code','users.phone','services.name as service')->join('users', 'users.id', '=', 'service_quotes.user_id')->join('services', 'services.id', '=', 'service_quotes.service_id')->with(['pets','appointment_types','feeding_schedules','doctor','groomer','play_staff','grooming_type'])->where(['service_quotes.deleted' => 0])->orderBy('service_quotes.created_at', 'desc');
        
        if(\Auth::user()->role == 5){
            $datamain = $datamain->where('service_quotes.service_id',1);
            $datamain = $datamain->where('service_quotes.doctor_id',\Auth::user()->doctor->id ?? 0);
            if($service == 0 && $service != ''){
                $datamain = $datamain->where('service_quotes.status','!=',config('global.service_confirmed'));
            }
            if($service == 6){
                $datamain = $datamain->where('service_quotes.status',$service);
            }
            $datamain = $datamain->get();
            return view('doctor.service_quotes.list', compact('page_heading', 'datamain'));
        }else{
            if($service){
                $datamain = $datamain->where('service_quotes.service_id',$service);
            }
        }
        
        $datamain = $datamain->get();
        if ($request->excel != 'Export') {
            
            return view('admin.reports.service_quotes', compact('page_heading', 'datamain','service'));
        } else {
            
            $rows = array();
            $i = 1;
            foreach ($datamain as $key => $item) {
                $ordernumber = config('global.quote_prefix').date(date('Ymd', strtotime($item->created_at))).$item->id; 
                
                $rows[$key]['i'] = $i;
                $rows[$key]['Quote No'] = $ordernumber;
                $rows[$key]['customer'] = $item->customer;
                $rows[$key]['phone'] = $item->dial_code." ".$item->phone;
                if($service == 1)
                {
                $rows[$key]['doctor'] = $item->doctor->name??'';  
                $rows[$key]['appointment_types'] = $item->appointment_types->name??'';    
                }
                if($service == 2)
                {
                $rows[$key]['Groomer'] = $item->groomer->name??'';    
                }
                $rows[$key]['service_status'] = service_status($item->status);
                $rows[$key]['Quote Price'] = "-";
                if($item->status == config('global.service_quote_sent'))
                {
                $rows[$key]['Quote Price'] = $item->quote_price??'-';    
                }
                if($item->service_id == 5)
                {
                $rows[$key]['Booking  Price'] = config('global.default_currency_code')." ".$item->grand_total;    
                }

                
                $rows[$key]['created_date'] = web_date_in_timezone($item->created_at, 'd-M-Y h:i A');
                $rows[$key]['Pet name'] = '-';
                $rows[$key]['Species']      = ''; 
                $rows[$key]['Breed']        = ''; 
                $rows[$key]['DOB']          = ''; 
                $rows[$key]['Weight (lbs)'] = ''; 
                foreach ($item->pets as $value_pets) {
                    $rows[$key]['Pet name'] = $value_pets->pets->name??''; 
                    $rows[$key]['Species'] = $value_pets->pets->sps->name??''; 
                    $rows[$key]['Breed']        = $value_pets->pets->breed->name??''; 
                    $rows[$key]['DOB']          = $value_pets->pets->dob??''; 
                    $rows[$key]['Weight (lbs)'] = $value_pets->pets->weight??''; 
                }
                

                
                

                $i++;
            }
            if($service == 1)
            {
                $headings = [
                "#",
                "Quote No",
                "Customer",
                "Phone",
                "Doctor",
                "Appointment Type",
                "Status",
                "Quote Price",
                "Created",
                "Pet name",
                "Species",
                "Breed",
                "DOB",
                "Weight (lbs)",
               ];    
            }
            else if($service == 2)
            {
                $headings = [
                "#",
                "Quote No",
                "Customer",
                "Phone",
                "Groomer",
                "Status",
                "Quote Price",
                "Created",
                "Pet name",
                "Species",
                "Breed",
                "DOB",
                "Weight (lbs)",
               ];    
            }
            else if($service == 3)
            {
                $headings = [
                "#",
                "Quote No",
                "Customer",
                "Phone",
                "Status",
                "Quote Price",
                "Created",
                "Pet name",
                "Species",
                "Breed",
                "DOB",
                "Weight (lbs)",
                ];
            }
            else if($service == 4)
            {
                $headings = [
                "#",
                "Quote No",
                "Customer",
                "Phone",
                "Status",
                "Quote Price",
                "Created",
                "Pet name",
                "Species",
                "Breed",
                "DOB",
                "Weight (lbs)",
                ];
            }
            else if($service == 5)
            {
                $headings = [
                "#",
                "Booking No",
                "Customer",
                "Phone",
                "Status",
                "Quote Price",
                "Booking  Price",
                "Created",
                "Pet name",
                "Species",
                "Breed",
                "DOB",
                "Weight (lbs)",
                ];
            }
            else
            {
                $headings = [
                "#",
                //"Service",
                "Customer",
                "Phone",
                "Status",
                "Quote Price",
                "Booking  Price",
                "Created",
                "Pet name",
                "Species",
                "Breed",
                "DOB",
                "Weight (lbs)",
                ];
            }
            
            $coll = new ExportReports([$rows], $headings);
            $ex = Excel::download($coll, $service_name . date('d_m_Y_h_i_s') . '.xlsx');
            if (ob_get_length()) {
                ob_end_clean();
            }

            return $ex;
        }
    }
    public function vendors(REQUEST $request)
    {
        if (!check_permission('vendor_rep', 'View')) {
            abort(404);
        }
        $page_heading = "Pharmacy";
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $roles = [3];
        $list = User::select('users.*','company_name','trade_license','trade_license_expiry','bank_name','account_no','branch_name','company_brand')->leftjoin('vendor_details','vendor_details.user_id','=','users.id')
        ->leftjoin('bank_details','bank_details.user_id','=','users.id')
        ->where(['users.deleted' => 0])->whereIn('role', $roles)->orderBy('created_at', 'desc');
        if ($from_date != '') {
            $list = $list->where('created_at', '>=', gmdate('Y-m-d H:i:s', strtotime($from_date . ' 00:00:00')));
        }
        if ($to_date != '') {
            $list = $list->where('created_at', '<=', gmdate('Y-m-d H:i:s', strtotime($to_date . ' 23:59:59')));
        }

        if ($request->excel != 'Export') {
            $list = $list->paginate(10);
            return view('admin.reports.vendor_list', compact('page_heading', 'list', 'from_date', 'to_date'));
        } else {
            $list = $list->get();
            $rows = array();
            $i = 1;
            foreach ($list as $key => $val) {
                $bankname = BankModel::find($val->bank_name);
                
                $rows[$key]['i'] = $i;
                $rows[$key]['name'] = $val->name;
                $rows[$key]['company_name'] = $val->company_name;
                $rows[$key]['company_brand'] = $val->company_brand;
                $rows[$key]['email'] = $val->email;
                $rows[$key]['phone'] = ($val->dial_code != '') ? $val->dial_code . ' ' . $val->phone : '-';
                $rows[$key]['location'] = $val->location;
                $rows[$key]['trade_license'] = $val->trade_license;
                $rows[$key]['trade_license_expiry'] = empty($val->trade_license_expiry)?'-': date('Y-m-d', strtotime($val->trade_license_expiry));
                $rows[$key]['bank_name'] = empty($bankname->name)?'-': $bankname->name;
                $rows[$key]['account_number'] = empty($val->account_no)?'-': $val->account_no;
                $rows[$key]['branch_name'] = empty($val->branch_name)?'-': $val->branch_name;
                $rows[$key]['created_date'] = web_date_in_timezone($val->created_at, 'd-M-Y h:i A');

                $i++;
            }
            $headings = [
                "#",
                "Name",
                "Company name",
                "Company Brand Name",
                "Email",
                "Mobile",
                "Location",
                "Trade license NO",
                "Trade license expiry",
                "Bank name",
                "Account number",
                "Branch name",
                "Created Date",
            ];
            $coll = new ExportReports([$rows], $headings);
            $ex = Excel::download($coll, 'vendors_' . date('d_m_Y_h_i_s') . '.xlsx');
            if (ob_get_length()) {
                ob_end_clean();
            }

            return $ex;
        }
    }

    public function commission(Request $request)
    {
        $page_heading = "Orders"; 
        $order_id = $_GET['order_id'] ?? '';
        $name = $_GET['name'] ?? '';
        $from = !empty($_GET['from'])?date('Y-m-d',strtotime($_GET['from'])): '';
        $to = !empty($_GET['to']) ?date('Y-m-d',strtotime($_GET['to'])): '';
        $status = $_GET['status'] ?? '';
        $list =  OrderModel::select('orders.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as customer_name"),'users.name')->leftjoin('users','users.id','orders.user_id')->with(['customer'=>function($q) use($name){
           $q->where('display_name','like','%'.$name.'%');
        }]);

        if (isset($name)) {
            $params['search_key'] =$name;
            $list->where(function($query) use($params) {
                            foreach (['users.name','users.first_name','users.last_name'] as $t_like_field) {
                                $query->orWhere("{$t_like_field}",'ilike',"%".$params['search_key']."%");
                            }
                        });
        }
        // if($name)
        // {
        //      $list =$list->whereRaw("concat(first_name, ' ', last_name) like '%" .$name. "%' ");
        // }
        if($order_id){
            $list=$list->where(function ($query) use ($order_id) {
            $query->where('orders.invoice_id','like','%'.$order_id.'%' );
            //$query->orWhere('orders.order_no', "like", "%" . $order_id . "%");
        });
        }
        if($from){
            $list=$list->whereDate('orders.created_at','>=',$from.' 00:00:00');
        }
        if($to){
            $list=$list->where('orders.created_at','<=',$to.' 23:59:59');
        }
       
            $list=$list->where('orders.status',config('global.order_status_delivered'));
        
        if(isset($_GET['status'])){
            if($_GET['status'] == 0)
            {
            $list=$list->where('orders.status',0);    
            }
            
        }
        $list =$list->orderBy('orders.order_id','DESC')->where('ordertype',0);
        $datacalc = $list;
        $datacalc = $datacalc->get();
        $total_admin_commission = 0;
        foreach ($datacalc as $key => $value) {

          $total_admin_commission = $total_admin_commission + number_format(OrderProductsModel::where('order_id',$value->order_id)->sum('admin_commission'), 2, '.', '');
        }
       
        
        
        foreach ($list as $key => $value) {
            $list[$key]->admin_commission = number_format(OrderProductsModel::where('order_id',$value->order_id)->sum('admin_commission'), 2, '.', '');
            $list[$key]->vendor_commission = number_format(OrderProductsModel::where('order_id',$value->order_id)->sum('vendor_commission'), 2, '.', '');
            $list[$key]->customer_name = $value->name??$value->customer_name;
            
        }
       
        
        if ($request->excel != 'Export') {
             if(isset($_GET['post']))
            {
            $list = $list->paginate(1000);  
            }
            else
            {
             $list = $list->paginate(10);  
            }
            return view('admin.reports.commission',compact('page_heading','list','order_id','name','from','to','status','total_admin_commission'));
        } else {
            $list = $datacalc;
            $rows = array();
            $i = 1;
            foreach ($list as $key => $item) {
                

                $rows[$key]['i'] = $i;
                $rows[$key]['ord_no'] = config('global.sale_order_prefix').date(date('Ymd', strtotime($item->created_at))).$item->order_id;
                $rows[$key]['invoice_no'] = ($item->invoice_id) ?? '-';
                $rows[$key]['customer'] = $item->customer_name ?? '-';
                $rows[$key]['discount'] = ($item->discount) ?? '-';
                $rows[$key]['service_charge'] = $item->service_charge;
                $rows[$key]['shipping_charge'] = ($item->shipping_charge) ?? 0;
                $rows[$key]['total'] = ($item->grand_total) ?? 0;
                $rows[$key]['admin_commission'] = ($item->admin_commission) ?? 0;
                $rows[$key]['vendor_earning'] = ($item->vendor_commission) ?? 0;
                $rows[$key]['payment_mode'] = payment_mode($item->payment_mode);
                $rows[$key]['order_status'] = order_status($item->status);
                $rows[$key]['created_at'] = get_date_in_timezone($item->created_at, 'd-M-y h:i A');
                $rows[$key]['booking_date'] = web_date_in_timezone($item->booking_date, 'd-M-Y h:i A');

                $i++;
            }
            $headings = [
                "#",
                "Order No",
                "Invoice NO",
                "Customer",
                "Discount",
                "Service Charge",
                "Shipping Charge",
                "Total",
                "Admin Commission",
                "Vendor Earning",
                "Payment Mode",
                "Order Status",
                "Created Date",
                "Booking Date",

            ];
            $coll = new ExportReports([$rows], $headings);
            $ex = Excel::download($coll, 'order_commission_' . date('d_m_Y_h_i_s') . '.xlsx');
            if (ob_get_length()) {
                ob_end_clean();
            }

            return $ex;
        }
    }

    public function commission_old(Request $request)
    {
        if (!check_permission('orders', 'View')) {
            abort(404);
        }
        $page_heading = "Commission Report";
        $order_id = $_GET['order_id'] ?? '';
        $name = $_GET['name'] ?? '';
        $customer = $_GET['customer'] ?? '';
        $from = !empty($_GET['from']) ? date('Y-m-d', strtotime($_GET['from'])) : '';
        $to = !empty($_GET['to']) ? date('Y-m-d', strtotime($_GET['to'])) : '';
        $vendor = \request()->get('vendor');
        $vendor_name = $_GET['vendor_name'] ?? '';

        $list =  OrderModel::select('orders.*',DB::raw("CONCAT(users.first_name,' ',users.last_name) as customer_name"),'users.name','u1.name as vendor')
        ->leftjoin('users as u1', 'u1.id', 'orders.vendor_id')
        ->leftjoin('users','users.id','orders.user_id')->with(['customer'=>function($q) use($name){
           $q->where('display_name','like','%'.$name.'%');
        }]);

      
        if ($vendor_name) {
            $list = $list->whereRaw("u1.name like '%" . $vendor_name . "%' ");
        }
        if ($name) {
            $list = $list->whereRaw("u2.name like '%" . $name . "%' ");
        }
         if($order_id){
            $list=$list->where(function ($query) use ($order_id) {
            $query->where('orders.invoice_id','like','%'.$order_id.'%' );
            //$query->orWhere('orders.order_no', "like", "%" . $order_id . "%");
        });
        }

        if($from){
            $list=$list->whereDate('orders.created_at','>=',$from.' 00:00:00');
        }
        if($to){
            $list=$list->where('orders.created_at','<=',$to.' 23:59:59');
        }
        if ($request->excel != 'Export') {
            $list = $list->orderBy('orders.order_id', 'DESC')->paginate(10);
            return view('admin.reports.commission', compact('page_heading', 'list', 'order_id', 'name', 'from', 'to', 'vendor', 'vendor_name'));
        } else {
            $list = $list->get();
            $rows = array();
            $i = 1;
            foreach ($list as $key => $val) {
                $mode = '-';
                if ($val->payment_method == 1) {$mode = "Wallet";}
                if ($val->payment_method == 2) {$mode = "Card";}
                if ($val->payment_method == 3) {$mode = "Apple Pay";}

                $rows[$key]['i'] = $i;
                $rows[$key]['ord_no'] = config('global.sale_order_prefix') . date(date('Ymd', strtotime($val->created_at))) . $val->id;

                $rows[$key]['order_number'] = ($val->order_number) ?? '-';
                $rows[$key]['vendor'] = $val->vendor ?? '-';
                $rows[$key]['customer'] = ($val->customer) ?? '-';
                $rows[$key]['total'] = $val->total_amount;
                $rows[$key]['admin_commission'] = ($val->admin_commission) ?? 0;
                $rows[$key]['mode'] = $mode;
                $rows[$key]['booking_date'] = web_date_in_timezone($val->booking_date, 'd-M-Y h:i A');

                $i++;
            }
            $headings = [
                "#",
                "Order No",
                "Invoice ID",
                "Vendor",
                "Customer",
                "Total",
                "Admin Commission",
                "Payment Mode",
                "Order Date",
            ];
            $coll = new ExportReports([$rows], $headings);
            $ex = Excel::download($coll, 'admin_commission_' . date('d_m_Y_h_i_s') . '.xlsx');
            if (ob_get_length()) {
                ob_end_clean();
            }

            return $ex;
        }

    }

    public function vendor_commission_request(Request $request)
    {
        if (!check_permission('orders', 'View')) {
            abort(404);
        }

        $store_id = auth()->user()->id;
        $page_heading = "Pharmacy Commission Report";
        $order_id = $_GET['order_id'] ?? '';
        $name = $_GET['name'] ?? '';
        $customer = $_GET['customer'] ?? '';
        $from = !empty($_GET['from']) ? date('Y-m-d', strtotime($_GET['from'])) : '';
        $to = !empty($_GET['to']) ? date('Y-m-d', strtotime($_GET['to'])) : '';
        $status = isset($_GET['withdraw_request']) ? $_GET['withdraw_request'] : '';
        $vendor = \request()->get('vendor');
        $vendor_name = $_GET['vendor_name'] ?? '';

        $list = OrderModel::select('orders.*', 'u1.name as vendor', 'u2.name as customer')
            ->leftjoin('users as u1', 'u1.id', 'orders.store_id')
            ->leftjoin('users as u2', 'u2.id', 'orders.user_id');
        if ($vendor_name) {
            $list = $list->whereRaw("u1.name like '%" . $vendor_name . "%' ");
        }
        if ($name) {
            $list = $list->whereRaw("u2.name like '%" . $name . "%' ");
        }
        if ($order_id) {
            $list = $list->where('orders.invoice_id', 'like', '%' . $order_id . '%');
        }

        if ($from) {
            $list = $list->whereDate('orders.created_at', '>=', $from . ' 00:00:00');
        }
        if ($to) {
            $list = $list->where('orders.created_at', '<=', $to . ' 23:59:59');
        }
        if ($status!='') {
            $list = $list->where('orders.withdraw_request',$status);
        }

        //$list = $list->where('orders.store_id', $store_id);
        $list = $list->where('orders.status', 6)->whereIn('withdraw_request',['1','2']);;

        if ($request->excel != 'Export') {
            $list = $list->orderBy('orders.order_id', 'desc')->paginate(10);
            return view('admin.reports.vendor_commission_request', compact('page_heading', 'list', 'order_id', 'name', 'from', 'to', 'vendor', 'vendor_name','status'));
        } else {
            $list = $list->get();
            $rows = array();
            $i = 1;
            foreach ($list as $key => $val) {

                $mode = '-';

                if ($val->payment_mode == 1) {$mode = "Wallet";}
                if ($val->payment_mode == 2) {$mode = "Card";}
                if ($val->payment_mode == 3) {$mode = "Apple Pay";}
                if ($val->payment_mode == 4) {$mode = "COD";}

                $rows[$key]['i'] = $i;
                $rows[$key]['ord_no'] = config('global.sale_order_prefix') . date(date('Ymd', strtotime($val->created_at))) . $val->id;

                $rows[$key]['order_number'] = ($val->invoice_id) ?? '-';
                $rows[$key]['vendor'] = $val->vendor ?? '-';
                $rows[$key]['customer'] = ($val->customer) ?? '-';
                $rows[$key]['total'] = $val->total;
                $rows[$key]['vendor_commission'] = ($val->vendor_commission) ?? 0;
                $rows[$key]['mode'] = $mode;
                $rows[$key]['booking_date'] = web_date_in_timezone($val->booking_date, 'd-M-Y h:i A');

                $i++;
            }
            $headings = [
                "#",
                "Order No",
                "Invoice ID",
                "Vendor",
                "Customer",
                "Total",
                "Admin Commission",
                "Payment Mode",
                "Order Date",
            ];
            $coll = new ExportReports([$rows], $headings);
            $ex = Excel::download($coll, 'vendor_commission_' . date('d_m_Y_h_i_s') . '.xlsx');
            if (ob_get_length()) {
                ob_end_clean();
            }

            return $ex;
        }

    }

    public function driver_commission_request(Request $request)
    {
        if (!check_permission('orders', 'View')) {
            abort(404);
        }

        $store_id = auth()->user()->id;
        $page_heading = "Driver Commission Report";
        $order_id = $_GET['order_id'] ?? '';
        $name = $_GET['name'] ?? '';
        $customer = $_GET['customer'] ?? '';
        $from = !empty($_GET['from']) ? date('Y-m-d', strtotime($_GET['from'])) : '';
        $to = !empty($_GET['to']) ? date('Y-m-d', strtotime($_GET['to'])) : '';
        $vendor = \request()->get('vendor');
        $vendor_name = $_GET['vendor_name'] ?? '';
        $status = isset($_GET['withdraw_request']) ? $_GET['withdraw_request'] : '';
        $list = OrderModel::select('orders.*', 'u1.name as vendor', 'u2.name as customer','u3.name as driver_name')
            ->leftjoin('users as u1', 'u1.id', 'orders.store_id')
            ->leftjoin('users as u3', 'u3.id', 'orders.driver_id')
            ->leftjoin('users as u2', 'u2.id', 'orders.user_id');
        if ($vendor_name) {
            $list = $list->whereRaw("u1.name like '%" . $vendor_name . "%' ");
        }
        if ($name) {
            $list = $list->whereRaw("u2.name like '%" . $name . "%' ");
        }
        if ($order_id) {
            $list = $list->where('orders.invoice_id', 'like', '%' . $order_id . '%');
        }

        if ($from) {
            $list = $list->whereDate('orders.created_at', '>=', $from . ' 00:00:00');
        }
        if ($to) {
            $list = $list->where('orders.created_at', '<=', $to . ' 23:59:59');
        }
        if ($status!='') {
            $list = $list->where('orders.driver_withdraw_request',$status);
        }

        //$list = $list->where('orders.store_id', $store_id);
        $list = $list->where('orders.status', 6)->whereIn('driver_withdraw_request',['1','2']);

        if ($request->excel != 'Export') {
            $list = $list->orderBy('orders.order_id', 'desc')->paginate(10);
            return view('admin.reports.driver_commission_request', compact('page_heading', 'list', 'order_id', 'name', 'from', 'to', 'vendor', 'vendor_name','status'));
        } else {
            $list = $list->get();
            $rows = array();
            $i = 1;
            foreach ($list as $key => $item) {

                 if($item->driver_withdraw_request==0)
                 {

                       $status = "UnPaid";                        
                 }
                 if($item->driver_withdraw_request==1)
                 {
                      $status = "Requested";
                 }
                 if($item->driver_withdraw_request==2)
                 {
                    $status =  "PAID";
                  }

               
                $rows[$key]['i'] = $i;
                $rows[$key]['ord_no'] = $item->invoice_id;

                $rows[$key]['driver'] = ($item->driver_name) ?? '-';
                $rows[$key]['total'] = $item->total ?? '-';
                $rows[$key]['commission'] = ($item->shipping_charge) ?? '-';
                $rows[$key]['booking_date'] = web_date_in_timezone($item->booking_date,'d-M-Y h:i A');
                $rows[$key]['status'] = ($status) ?? "";
                

                $i++;
            }
            $headings = [
                "#",
                "Order No",
                "Driver",
                "Total",
                "Commission",
                "Order Date",
                "Withdraw Status",
            ];
            $coll = new ExportReports([$rows], $headings);
            $ex = Excel::download($coll, 'vendor_commission_' . date('d_m_Y_h_i_s') . '.xlsx');
            if (ob_get_length()) {
                ob_end_clean();
            }

            return $ex;
        }

    }
    public function qtcommission(Request $request)
    {
        $page_heading = "Quotation Commission";
        $params = [];
        $from = $params['from'] =  !empty($_GET['from'])?date('Y-m-d',strtotime($_GET['from'])): '';
        $to = $params['to'] = !empty($_GET['to']) ?date('Y-m-d',strtotime($_GET['to'])): '';
        $order_id = $params['request_number'] = !empty($_GET['order_id'])? ltrim($_GET['order_id'],'#'): '';
        $name = $params['name'] = !empty($_GET['name'])? $_GET['name']: '';
        $status = $params['status'] = !empty($_GET['status'])? $_GET['status']: '';

       
        $limit   =   $request->limit ? $request->limit  : 10;
        $page    =  $request->page ? $request->page  : 1;
        $start   =  ($page-1)*$limit;
        
        $list = Quotation::select('quotation.*')->with('doneBy')->leftjoin('users','users.id','=','quotation.user_id');
        if(!empty($params['id'])) {
            $list = $list->where('quotation.id',$params['id']);
        }
        if(!empty($params['request_number'])) {
            $list = $list->where('quotation.request_number',$params['request_number']);
        }
        if(!empty($params['from'])){
            $list=$list->whereDate('quotation.created_at','>=',$params['from'].' 00:00:00');
        }
        if(!empty($params['to'])){
            $list=$list->where('quotation.created_at','<=',$params['to'].' 23:59:59');
        }
            $list=$list->where('quotation.order_status',config('global.order_status_delivered'));
      
        if(!empty($params['name'])){
            $list=$list->where('users.name', 'LIKE', '%'.$params['name'].'%');
        }
        
        
        $list = $list->orderBy('quotation.id','desc');
        $datacalc = $list;
        $datacalc = $datacalc->get();
        $total_admin_commission = 0;
        foreach ($datacalc as $key => $value) {

          $total_admin_commission = $total_admin_commission + number_format(Quotation::where('id',$value->id)->sum('service_charge'), 2, '.', '');
        }

        
        
        if ($request->excel != 'Export') {
            $list = $list = $list->paginate(10);;
            return view('admin.reports.quotation',compact('page_heading','order_id','name','from','to','list','status','total_admin_commission'));
        } else {
            $list = $datacalc;
            $rows = array();
            $i = 1;
            foreach ($list as $key => $item) {


                $rows[$key]['i'] = $i;
                $rows[$key]['invoice_id'] = $item->request_number;
                $rows[$key]['customer'] = $item->doneBy->name;
                $rows[$key]['date'] = web_date_in_timezone($item->created_at,"d M Y - h:i A");
                $rows[$key]['admin_commission'] = ($item->service_charge) ?? '-';

                $i++;
            }
            $headings = [
                "#",
                "Invoice ID",
                "Customer",
                "Order Date",
                "Admin Commission",
            ];
            $coll = new ExportReports([$rows], $headings);
            $ex = Excel::download($coll, 'quatation_commission_' . date('d_m_Y_h_i_s') . '.xlsx');
            if (ob_get_length()) {
                ob_end_clean();
            }

            return $ex;
        }
    }
     
}
