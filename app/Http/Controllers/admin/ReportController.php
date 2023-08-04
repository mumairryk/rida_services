<?php

namespace App\Http\Controllers\admin;

use App\Exports\ExportReports;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WholesaleOrder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{

    public function vendors(REQUEST $request)
    {
        if (!check_permission('vendor_rep', 'View')) {
            abort(404);
        }
        $page_heading = "Vendors";
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $roles = [1, 2, 3, 4, 5, 6];
        $list = User::where(['users.deleted' => 0])->whereIn('user_type_id', $roles)->orderBy('created_at', 'desc');
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
                $type = "Commercial Centers";
                if ($val->user_type_id == 2) {$type = "Reservations";}
                if ($val->user_type_id == 3) {$type = "Individuals";}
                if ($val->user_type_id == 4) {$type = "Service Providers";}
                if ($val->user_type_id == 5) {$type = "WholeSellers";}
                if ($val->user_type_id == 6) {$type = "Delivery Representative";}
                $rows[$key]['i'] = $i;
                $rows[$key]['name'] = $val->name;
                $rows[$key]['email'] = $val->email;
                $rows[$key]['phone'] = ($val->dial_code != '') ? $val->dial_code . ' ' . $val->phone : '-';
                $rows[$key]['type'] = $type;
                $rows[$key]['created_date'] = web_date_in_timezone($val->created_at, 'd-M-Y h:i A');

                $i++;
            }
            $headings = [
                "#",
                "Name",
                "Email",
                "Mobile",
                "Registration Type",
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
        if (!check_permission('orders', 'View')) {
            abort(404);
        }
        $page_heading = "Admin Commission Report";
        $order_id = $_GET['order_id'] ?? '';
        $name = $_GET['name'] ?? '';
        $customer = $_GET['customer'] ?? '';
        $from = !empty($_GET['from']) ? date('Y-m-d', strtotime($_GET['from'])) : '';
        $to = !empty($_GET['to']) ? date('Y-m-d', strtotime($_GET['to'])) : '';
        $vendor = \request()->get('vendor');
        $vendor_name = $_GET['vendor_name'] ?? '';

        $list = WholesaleOrder::select('wholesale_orders.*', 'u1.name as vendor', 'u2.name as customer')
            ->leftjoin('users as u1', 'u1.id', 'wholesale_orders.vendor_id')
            ->leftjoin('users as u2', 'u2.id', 'wholesale_orders.user_id');
        if ($vendor_name) {
            $list = $list->whereRaw("u1.name like '%" . $vendor_name . "%' ");
        }
        if ($name) {
            $list = $list->whereRaw("u2.name like '%" . $name . "%' ");
        }
        if ($order_id) {
            $list = $list->where('wholesale_orders.order_number', 'like', '%' . $order_id . '%');
        }

        if ($from) {
            $list = $list->whereDate('wholesale_orders.created_at', '>=', $from . ' 00:00:00');
        }
        if ($to) {
            $list = $list->where('wholesale_orders.created_at', '<=', $to . ' 23:59:59');
        }
        if ($request->excel != 'Export') {
            $list = $list->orderBy('wholesale_orders.id', 'DESC')->paginate(10);
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

}
