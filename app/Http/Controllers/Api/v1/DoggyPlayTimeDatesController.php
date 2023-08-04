<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\AppointmentTypes;
use App\Models\Breeds;
use App\Models\ContactUs;
use App\Models\DoctorCalender;
use App\Models\Doctors;
use App\Models\FeedingSchedules;
use App\Models\Foods;
use App\Models\GroomerCalender;
use App\Models\Groomers;
use App\Models\GroomingTypes;
use App\Models\MyPets;
use App\Models\RoomTypes;
use App\Models\Species;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use App\Models\ServiceQuotes;
use App\Models\VendorModel;
use App\Models\VendorServiceTimings;
use App\Models\DoggyPlayTimeDates;
use DateTime;
class DoggyPlayTimeDatesController extends Controller
{
    public function get_doggy_play_time_dates_list(Request $request){
            
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];

        $now = time_to_uae(now(),'Y-m-d H:i:s');
        $dates = DoggyPlayTimeDates::where('date', '>=',$now)->orderBy('date', 'ASC')
        // ->where('vendor_id', $ID)
        ->get();
        $Months = [];
        $Dates = [];
        $vendorIds = \App\Models\VendorModel::where(['role'=>'3','deleted'=>'0'])->orderBy('id','desc')->pluck('id')->toArray();
        $holiday_dates = \App\Models\VendorHolidayDates::where([['date','>=',time_to_uae(now(),'Y-m-d')]])->whereIn('vendor_id',$vendorIds)->pluck('date')->toArray();

        foreach ($dates as $key => $Date) {
            $DateTime = new DateTime($Date->date);

            if(in_array($DateTime->format('Y-m-d'), $holiday_dates)){
                continue;
            }
            
            $filled = 0;
            if($key == 0){
                // $OrderBooked = \App\OrderMain::select('filled_slots')->where('dining_id', $Dining->id)->orderBy('id','desc');
                // $OrderBooked = $OrderBooked->whereDate('dining_date',  $DateTime->format('Y-m-d'));
                $filled      = 0;//$OrderBooked->get()->sum('filled_slots') ?? 0;
            }

            array_push($Dates, [
                    'dates'             => $DateTime->format('Y-m-d'),
                    'days'              => $DateTime->format('M d'),
                    'month'             => $DateTime->format('F-Y'),
                    // 'total_seats'         => $Date->total_seats,
                    // 'filled_slots'      => $filled ?? 0,
                    // 'available_slots'   => $Date->total_seats - ($filled ?? 0),
                 ]);
            if(!in_array($DateTime->format('F-Y'), $Months)){
                array_push($Months, $DateTime->format('F-Y'));
            }
        }


        // change date version
        // $filled = abs($filled);
        // $db_date = DoggyPlayTimeDates::whereDate('date',  date('Y-m-d', strtotime($Dates[0]['dates'])))
        // // ->where('vendor_id', $ID)
        // ->first();

        // $available_slots = (($db_date->total_seats) - $filled);
        $o_data = [
            'dates' => $Dates,
            'months' => $Months,
            // 'total_seats' => $db_date->total_seats,
            // 'filled_slots' => ($filled ?? 0),
            // 'available_slots' => ($available_slots < 0 ? 0 : $available_slots),
        ];
        $o_data = convert_all_elements_to_string($o_data);
        return  response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' =>  (object) $o_data], 200);

    }
    public function get_doggy_play_time_date(Request $request, $date){
            
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];

        $db_date = DoggyPlayTimeDates::whereDate('date',  date('Y-m-d', strtotime($date)))
        // ->where('vendor_id', $ID)
        ->where('date', '>=',time_to_uae(now(),'Y-m-d H:i:s'))
        ->first();

        $vendorIds = \App\Models\VendorModel::where(['role'=>'3','deleted'=>'0'])->orderBy('id','desc')->pluck('id')->toArray();
        $holiday_dates = \App\Models\VendorHolidayDates::where([['date','>=',time_to_uae(now(),'Y-m-d')]])->whereIn('vendor_id',$vendorIds)->pluck('date')->toArray();

        $DateTime = new DateTime($db_date->date);
        $available_slots = 0;
        if(!in_array($DateTime->format('Y-m-d'), $holiday_dates)){
           $filled = ServiceQuotes::where('date', '>=',time_to_uae(now(),'Y-m-d H:i:s'))
                // ->select('seats')
                ->where('service_id', 5)
                ->whereIn('status',[0,1,3])
                ->whereDate('date',  $date)
                ->sum('seats');
            $available_slots = (($db_date->total_seats ?? 0) - $filled);

            $filled = abs($filled);
        }else{
            $db_date = new DoggyPlayTimeDates();
        }
        $o_data = [
            'price'       => $db_date->price ?? 0,
            'total_seats'       => $db_date->total_seats ?? 0,
            'filled_slots'      => $filled ?? 0,
            'available_slots'   => ($available_slots < 0 ? 0 : $available_slots),
        ];
        $o_data = convert_all_elements_to_string($o_data);
        return  response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' =>  (object) $o_data], 200);

    }

    

}
