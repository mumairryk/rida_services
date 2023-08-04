<?php

namespace App\Http\Controllers\Admin;
use App\Models\VendorModel;
use App\Models\DoggyPlayTimeDates;

use App\DbNotification;
use App\DiningCancelRequestDate;
use App\DiningDates;
use App\DiningDeleteRequest;
use App\Http\Controllers\Controller;
use App\OrderDetail;
use App\OrderMain;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Storage;

class DoggyPlayTimeDatesController extends Controller
{
    use SoftDeletes;


    public function dining_update_dates_data()
    {
        // update once if dining dates not having time and seats
        $dates = DiningDates::whereDate('date','>=',date('Y-m-d', strtotime(' +4 hours', strtotime(now()))))->with('dining')->orderBy('date')->get();
        foreach ($dates as $key => $date) {

            $start_time = $date->time_start ? Carbon::parse($date->time_start)->format('h:i A') : '';
            $end_time = $date->time_end ? Carbon::parse($date->time_end)->format('h:i A') : '';
            $is_passed_date = Carbon::parse($date->date)->isPast();

            if(!$is_passed_date){
                if(!$date->time_start){
                    // // update the other detail wihout date
                    $DateTime                    = Carbon::parse($date->date);
                    $date->date                  =  $DateTime->format('Y-m-d') . ' ' . Carbon::parse($date->dining->time_start)->format('H:i:s');
                    $date->time_start            =  Carbon::parse($date->dining->time_start)->format('H:i:s');
                    $date->time_end              =  Carbon::parse($date->dining->time_end)->format('H:i:s');
                    $date->total_seats           =  $date->dining->total_seats;
                    $date->seats                 =  $date->dining->seats;
                    $date->guests_booking        =  $date->dining->guests_booking;
                    $date->save();
                }
            }
        }
            
        return DiningDates::whereDate('date','>=',date('Y-m-d', strtotime(' +4 hours', strtotime(now()))))->with('dining')->orderBy('date')->get();
    }

    public function getDiningDates($vendor_id)
    {
        $vendor = VendorModel::where('id', $vendor_id)->first();
        $dates = DoggyPlayTimeDates::where('vendor_id', '=', $vendor_id)->orderBy('date')->get();
        $html = '';
        $dates_to_disable = [];
        foreach ($dates as $key => $date) {

            $formatted_date = Carbon::parse($date->date)->format("d-m-Y");
            $formatted_date_for_label = Carbon::parse($date->date)->format("d F Y");
            $start_time = $date->time_start ? Carbon::parse($date->time_start)->format('h:i A') : '';
            $end_time = $date->time_end ? Carbon::parse($date->time_end)->format('h:i A') : '';
            $dates_to_disable[] = $formatted_date;
            $is_passed_date = Carbon::parse($date->date)->isPast();
            $bookings = $this->getBookingsByDate($vendor_id, $date);
            $seats = $date->seats ? $date->seats : 0;
            $time = $start_time ? $start_time.'-'.$end_time : '';
            $booking_count = 0;//$bookings->count() ?? 0;
            $seats_count  = 0;
            if($booking_count){
                // $Order = $this->get_order_on_selected_date($dining_id,$date);
                $seats_count = $bookings->sum('confirmed')??0;// $Order->orderdetail->where('is_waiting', false)->count() ?? 0;
            }
                // <td>' .$time.'</td>

                // <td>'.($date->seats).'</td>

                // <td class="text-center"><b>' . $seats_count  . '</b></td>

                // <td class="text-center"><b>' . $booking_count . '</b></td>

                // <td class="text-center"><b>' . aed(0) . '</b></td>

            $function = "editDate('".date('d-F-Y',strtotime($date->date))."',". $date->price.",". $seats_count.",".$date->total_seats.",". $date->seats.",". $date->vendor_id . "," . $date->id . ",'" . $date->time_start. "','" . $date->time_end . "')";
            $html .= '<tr>
                <td>' . ($key + 1) . '</td>
                <td>' . $formatted_date_for_label .'</td>
                <td>AED '.($date->price).'</td>
                <td>'.($date->total_seats).'</td>
                <td>';
            if ($is_passed_date) {
                $html .= '<span class="badge badge-success">Completed</span>';
            } else {

            $html .= $date->is_cancel_requested ? '<span class="badge badge-danger" title="Cancellation Requested">CR</span>' : '<span class="badge badge-info">Active</span>';
            }
            $html .= '</td>
                <td>';

            $edit_check  = '<input type="checkbox" class="checkbox delete-date-checkbox"  name="date_ids[]" value="' . $date->id . '" /> ';
            if($booking_count){
            }
                $edit_check .= '<a onclick='.$function.' class="btn btn-warning btn-sm">
                    <i class="fa fa-pencil"></i>
                </a>'; 

                
            $html .= !$date->is_cancel_requested && !$is_passed_date ? $edit_check :
                '<input type="checkbox" class="checkbox"  name="date_ids[]" value="0" disabled>';
            $html .= '</td>
            </tr>';
        }
        return [$html, $dates_to_disable,$vendor];
    }
    public function get_order_on_selected_date($dining_id,$date){

        $Order = \App\OrderMain::with('orderdetail')
                    ->where('dining_id', $dining_id)
                    ->orderBy('id','desc')
                    ->whereDate('dining_date', Carbon::parse($date->date)->format('Y-m-d'))
                    ->first();

        return  $Order;
    }
    public function notify_users_for_time_change($date,$Order,$changes,$seat_increased ,$seat_changed ){

        // update the master order on time and seats change 
        if($Order){
            // update the master order on time change only
            //send automated message to booking owners if time has changes and if booking exist
            $notify = new \App\Http\Controllers\chef\DiningNotifyController(app('firebase.database'));
                
            if($changes){
                $DateTimeStart              = new DateTime(($date->time_start)) ;
                $DateTimeEnd                = new DateTime(($date->time_end)) ;
                
                $order_date                 = Carbon::parse($Order->dining_date);
                $Order->dining_date         = $order_date->format('Y-m-d') . ' ' . $DateTimeEnd->format('H:i:s');
                $Order->time                = $DateTimeStart->format('h:i A').' - '. $DateTimeEnd->format('h:i A');
                $Order->start_time          = $DateTimeStart->format('h:i A');
                $Order->end_time            = $DateTimeEnd->format('h:i A');
                $Order->dining_date_with_start_time   = $order_date->format('Y-m-d') . ' ' . $DateTimeStart->format('H:i:s');
                $Order->save(); 
                
    
                $order_details = $Order->orderdetail;
                if($changes && $order_details->count()){
                    foreach ($order_details as $key => $row) {
                        $notify->notify_users_for_time_change_email($row,$Order);
                    }
                }
            }
            
            $Order->total_seats         = (int)$date->seats;
            $Order->seats_available     = (int)$date->seats - $Order->filled_slots;
            $Order->save(); 
                
            //send automated message to booking owners if seats has changes and seat_increased and seats are avaiable for booking
            if($seat_increased){
                $notify->notify_users_for_waiting_seats_avaiability($Order);
            }
        }
    }

    public function addDateToService(Request $request)
    {
        $response['message'] = 'Date(s) added successfully';
        $response['status'] = true;
        $already_added = [];
        DB::beginTransaction();
        try {
            // $Dining = \App\Dining::where('id', $request->dining_id)->first();
            if(!$request->total_seats){
                $request->merge(['total_seats' => 0]);
            }

            $DateTimeStart = new DateTime(($request->start_time ?? now())) ;
            $DateTimeEnd = new DateTime(($request->end_time ?? now())) ;

            if(!$request->date_id && !($request->dates)){
                $response['message'] = "Select the experience date.";
                $response['status'] = false;
                return response()->json($response);
            }
            if(!($request->price > 0)){
                $response['message'] = "Price is required";
                $response['status'] = false;
                return response()->json($response);
            }
            if(!($request->total_seats > 0)){
                $response['message'] = "Enter the seats count.";
                $response['status'] = false;
                return response()->json($response);
            }

            // if(!($DateTimeStart < $DateTimeEnd)){
            //     $response['message'] = "End time can not be less than or equal than start time.";
            //     $response['status'] = false;
            //     return response()->json($response);
            // }
            if($request->date_id){
                $db_date = DoggyPlayTimeDates::where('id', $request->date_id)->where('vendor_id', $request->vendor_id)->first();
                if(!$db_date){
                    $response['message'] = "Unable to find date.";
                    $response['status'] = false;
                    return response()->json($response);
                }
                $Order = null;//$this->get_order_on_selected_date($request->vendor_id,$db_date);
                $seats_count = 0;//$Order ? $Order->orderdetail->where('order_status','!=','CANCELLED')->where('is_waiting', false)->count() : 0;

                
                if($request->total_seats < $seats_count){
                    $response['message'] = "Total seats can not be smaller that booked seats.";
                    $response['status'] = false;
                    return response()->json($response);
                }

                $changes = false;
                $seat_increased = false;
                if($db_date->time_start != $DateTimeStart->format('H:i:s') || $db_date->time_end != $DateTimeEnd->format('H:i:s')){
                    $changes = true;
                }
                if($request->seats > $db_date->seats){
                    // $changes = true;
                    $seat_increased = true;
                }
                $seat_changed = false;
                if($request->seats != $db_date->seats){
                    // $changes = true;
                    $seat_changed = true;
                }

                // update the other detail wihout date
                $db_DateTime                    = Carbon::parse($db_date->date);
                $db_date->date                  =  $db_DateTime->format('Y-m-d') . ' ' . $DateTimeStart->format('H:i:s');
                $db_date->time_start            =  $DateTimeStart->format('H:i:s');
                $db_date->time_end              =  $DateTimeEnd->format('H:i:s');
                $db_date->price                 =  $request->price ?? 0;
                $db_date->total_seats           =  $request->total_seats ?? 0;
                $db_date->seats                 =  $request->seats ?? 0;
                $db_date->guests_booking        =  $request->guests_booking ?? 0;
                $db_date->save();

                if($changes && $Order || $seat_increased && $Order ||  $seat_changed && $Order){
                    // $this->notify_users_for_time_change($db_date,$Order,$changes,$seat_increased,$seat_changed );
                }

                $response['message'] = "Date has been updated successfully";
                $response['date'] = $db_date;
                $response['status'] = true;
                DB::commit();

                return response()->json($response);
            }
            $Dates = explode(',', $request->dates);
            $existing_dates = DoggyPlayTimeDates::where('vendor_id', $request->vendor_id)->pluck('date')->toArray();
            $existing_dates = array_map(function ($date) {
                return Carbon::parse($date)->format('d-m-Y');
            }, $existing_dates);

            foreach ($Dates as $date) {
                $date = date('d-m-Y',strtotime($date));
                if (in_array($date, $existing_dates)) {
                    $already_added[] = $date;
                    continue;
                }
                $DateTime = Carbon::parse($date);

                $new_date = new DoggyPlayTimeDates();
                $new_date->date                  =  $DateTime->format('Y-m-d') . ' ' . $DateTimeStart->format('H:i:s');
                $new_date->time_start            =  $DateTimeStart->format('H:i:s');
                $new_date->time_end              =  $DateTimeEnd->format('H:i:s');
                $new_date->total_seats           =  $request->total_seats ?? 0;
                $new_date->price                 =  $request->price ?? 0;
                $new_date->seats                 =  $request->seats ?? 0;
                $new_date->guests_booking        =  $request->guests_booking ?? 0;
                $new_date->vendor_id             =  $request->vendor_id;
                $new_date->service_id             =  $request->service_id ?? 5;
                $new_date->save();
            }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $response['message'] = $e->getMessage();
            $response['status'] = false;
        }
        if (count($already_added) > 0) {
            DB::rollback();
            $response['message'] = "Few dates are already added: (" . implode(',', $already_added)."). So, they are not added again.";
            $response['status'] = true;
        }
        DB::rollback();
        return response()->json($response);
    }

    public function check_exciting_event(Request $request)
    {
        return 1;

        
        if (isset ($request->date_type) && $request->date_type == "multiple" && $request->chef_id > 0) {
            $dates = $request->dates;
            $dates = explode(',', $dates);

            if (isset ($dates)) {
                foreach ($dates as $index => $date) {

                    if ($date != "") {

                        $Dining = \App\DiningDates::
                        // select('id')->
                        join('dining as d', 'd.id', '=', 'dining_dates.dining_id')
                            ->whereDate('date', $date)
                            ->where('d.chef_id', $request->chef_id)->first();
                        if ($Dining) {
                            return 1;
                        }

                    }

                }
            }

        }

        return 0;
    }

    public  function deleteDates(Request $request)
    {

        $response = [
            'status' => 0,
            'message' => 'Something went wrong',
        ];
        $id = preg_replace('/[^0-9]/', '', $request->dining_id);

        $dates_id = $request->date_ids;
        $amount = 0;
        $orders = 0;
        $dates = [];
        $req_date_ids = [];
        if ($dates_id) {
            foreach ($dates_id as $index => $date_id) {
                $dp_date = DoggyPlayTimeDates::where('id',$date_id)->first();

                // $bookings = $this->getBookingsByDate($id, $dp_date);

                // if ($bookings->count() > 0) {
                //     $req_date_ids[] = $date_id;
                //     $dates[] = $dp_date->date;
                //     $amount += $bookings->sum('total');
                //     $orders += $bookings->count();
                // } else {
                    $dp_date->delete();
                    $response['status'] = 1;
                    $response['message'] = 'Date(s) deleted successfully';
                // }
            }
            if ($orders > 0 || $amount > 0) {
                $response['message'] = '<p class="text-left">You can not delete this/these dates (';
                foreach ($dates as $index => $date) {
                    $response['message'] .= Carbon::parse($date)->format('d-m-Y') . ', ';
                }
                $response['message'] .= ') because there are <b>' . $orders . '</b> active order(s). Please enter reason and submit deletion request to admin.
                </p>
                <p class="text-left text-danger mb-0"><b>Note:</b> The amount of <strong>' . CURRENCY() . FORMAT_NUMBER($amount) . '</strong> will be refunded from your account to guests.</p>';
                $response['status'] = 2;
                $response['date_ids'] = $req_date_ids;
            }

        }
        return response()->json($response);
    }

    public function deleteRequest(Request $request)
    {

        $response = [
            'status' => false,
            'message' => 'Something went wrong',
        ];

        $id = preg_replace('/[^0-9]/', '', $request->dining_id);
        $Dining = \App\Dining::where('id', $id)->first();
        $date_ids = $request->date_ids;
        $amount = 0;
        $active_orders = 0;
        if ($Dining) {
            foreach ($date_ids as $index => $date_id) {
                $dining_date = \App\DiningDates::where('id', $date_id)->first();
                $bookings = $this->getBookingsByDate($id, $dining_date);
                $active_orders += $bookings->count();
                $amount += $bookings->sum('total');

            }

            $diningDeleteRequest = DiningDeleteRequest::create([
                'dining_id' => $id,
                'reason' => $request->reason,
                'chef_id' => auth()->user()->id,
                'refund_amount' => $amount,

            ]);

            foreach ($date_ids as $index => $date_id) {
                $dining_date = \App\DiningDates::where('id', $date_id)->first();
                if (!$dining_date)
                    continue;
                $dining_date->is_cancel_requested = 1;
                $dining_date->save();
                $bookings = $this->getBookingsByDate($id, $dining_date);
                DiningCancelRequestDate::create([
                    'request_id' => $diningDeleteRequest->id,
                    'date_id' => $date_id,
                    'date' => Carbon::parse($dining_date->date)->format('Y-m-d'),
                    'active_orders' => $bookings->count(),
                    'amount' => $bookings->sum('total'),
                ]);
            }
            notifyAdmin($diningDeleteRequest->id, DbNotification::DINING_DELETED,
                DbNotification::NotificationTypes[DbNotification::DINING_DELETED],
                auth()->user()->name.' has requested to cancel experience '.$Dining->title
            );
            $this->sendEamilToAdmin($diningDeleteRequest, $Dining);
            $response['message'] = 'Cancellation request submitted to admin.';
            $response['status'] = true;
        } else {
            $response['message'] = 'Something went wrong';
            $response['status'] = false;
        }

        return response()->json($response);
    }

    /**
     * @param $dining_id
     * @param $date
     * @return mixed
     */
    public function getBookingsByDate($vendor_id, $date)
    {

        return null;

        // $bookings = OrderMain::join('order_details', 'order_details.order_id', '=', 'order_mains.id')
        //     ->where('order_details.dining_id', '=', $dining_id)
        //     ->whereDate('order_mains.dining_date',  Carbon::parse($date->date)->format('Y-m-d'))
        //     ->where('order_details.order_status', '=', 'INPROCESS')
        //     ->where('order_details.payment_status', '=', 'PAID')
        //     ->select('order_details.*')
        //     ->get();

        $data =  \App\OrderDetail::select(DB::raw('order_details.dining_id, order_details.chef_id, order_details.created_at, order_details.owner_id, order_details.price_person, order_details.order_status, order_details.confirm_number, COUNT(order_details.id) as seats, order_details.dining_id, order_details.order_id, order_details.total, COUNT(CASE WHEN order_details.is_waiting = true THEN is_waiting END) AS waiting, order_details.total, COUNT(CASE WHEN order_details.is_waiting = false THEN order_details.is_waiting END) AS confirmed'))
            ->join('order_mains', 'order_mains.id', '=', 'order_details.order_id')
            ->where('order_details.order_status', '!=', 'CANCELLED')
            ->whereHas('ordermain', function ($Query) use($date) {
                return $Query->whereDate('dining_date',  Carbon::parse($date->date)->format('Y-m-d'));
            })
            ->where('order_details.dining_id', $dining_id)
            ->groupBy('order_details.dining_id', 'order_details.chef_id', 'owner_id', 'price_person', 'order_status', 'confirm_number', 'order_id', 'order_details.total', 'order_details.created_at', 'order_mains.dining_date_with_start_time');
            $bookings = $data->get();
        return $bookings;
    }

}
