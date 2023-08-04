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
use App\Models\VendorHolidayDates;

class BaseController extends Controller
{
    public function species(Request $request)
    {
        $where['deleted'] = 0;
        $where['active'] = 1;
        $species = Species::select('id', 'name')->orderBy('name', 'asc')->where($where)->get();
        return response()->json([
            'status' => "1",
            'message' => trans('validation.data_fetched_successfully'),
            'errors' => [],
            'oData' => $species,
        ], 200);
    }
    public function breed(Request $request)
    {
        $where['breeds.deleted'] = 0;
        $where['breeds.active'] = 1;
        if ($request->species) {
            $where['species'] = $request->species;
        }
        $breed = Breeds::select('breeds.id', 'breeds.name', 'room_type_id as cage_type_id', 'room_types.name as cage_type', 'appoint_time_id', 'appointment_times.name as appointment_time', 'minutes_required as appointment_time_in_minutes')->leftjoin('room_types', 'room_types.id', 'room_type_id')->leftjoin('appointment_times', 'appointment_times.id', 'appoint_time_id')->orderBy('breeds.name', 'asc')->where($where)->get();
        return response()->json([
            'status' => "1",
            'message' => trans('validation.data_fetched_successfully'),
            'errors' => [],
            'oData' => $breed,
        ], 200);
    }
    public function cage_types(Request $request)
    {
        $where['deleted'] = 0;
        $where['active'] = 1;
        $cage = RoomTypes::where($where)->when($request->bread_id,function($q) use($request){
            return $q->whereHas('breads', function($Query) use($request) {
                     return $Query->where('id',$request->bread_id);
                 });
        })
        ->select('id', 'name')
        ->orderBy('name', 'asc')->get();
        return response()->json([
            'status' => "1",
            'message' => trans('validation.data_fetched_successfully'),
            'errors' => [],
            'oData' => $cage,
        ], 200);
    }
    public function food(Request $request)
    {
        $where['deleted'] = 0;
        $where['active'] = 1;

        $food = Foods::select('id', 'name', 'image')->orderBy('name', 'asc')->where($where);
        if (isset($request->search_text) && $request->search_text) {
            $srch = $request->search_text;
            $food = $food->whereRaw("(name ilike '%$srch%')");
        }
        $food = $food->get();
        return response()->json([
            'status' => "1",
            'message' => trans('validation.data_fetched_successfully'),
            'errors' => [],
            'oData' => $food,
        ], 200);
    }
    public function doctors(Request $request)
    {
        $where['deleted'] = 0;
        $where['active'] = 1;
        $interval = $request->interval ?? 30;
        if(!$request->interval){
            if($request->pet_ids){
                $pets = MyPets::select('id', 'name', 'breed_id', 'sex', 'dob', 'weight', 'food', 'additional_notes', 'active', 'species as species_id', 'image', 'medicine_instructions')->with(['breed' => function ($q) {
                    $q->select('breeds.id', 'breeds.name', 'room_type_id as cage_type_id', 'room_types.name as cage_type', 'appoint_time_id', 'appointment_times.name as appointment_time', 'minutes_required as appointment_time_in_minutes')->leftjoin('room_types', 'room_types.id', 'room_type_id')->leftjoin('appointment_times', 'appointment_times.id', 'appoint_time_id');
                }])->whereIn('id', $request->pet_ids)->get();
                foreach ($pets as $pet) {
                    if (isset($pet->breed->appointment_time_in_minutes)) {
                        if ($pet->breed->appointment_time_in_minutes > $interval) {
                            $interval = $pet->breed->appointment_time_in_minutes;
                        }
                    }
                }
            }
        }


        $data = Doctors::orderBy('name', 'asc')->where($where);
        if (isset($request->search_text) && $request->search_text) {
            $srch = $request->search_text;
            $data = $data->whereRaw("(name ilike '%$srch%')");
        }
        if($request->date){
            $Date = $request->date;
            $date = Carbon::parse($Date)->format('Y-m-d');
            $data = $data->whereHas('doctor_dates', function($Query) use($date) {
                     return $Query->where('event_date', '>=',time_to_uae(now(),'Y-m-d H:i:s'))
                     ->whereDate('event_date', $date);
                 })
                ->with(['doctor_dates'=> function($Query) use($date) {
                     return $Query->where('event_date', '>=',time_to_uae(now(),'Y-m-d H:i:s'))
                     ->whereDate('event_date', $date)->orderBy('event_date','asc');
                 }])
                ->with(['service_quotes'=> function($Query) use($date) {
                     return $Query->where('date', '>=',time_to_uae(now(),'Y-m-d H:i:s'))
                     ->whereDate('date', $date)
                     ->where('service_id',1)
                     ->whereIn('status',[0,1,3])
                     ->where('time_slot','!=','')->orderBy('date','asc');
                 }])
            ;
        }

        // $booked_time_slot_array = ServiceQuotes::where('date', '>=',time_to_uae(now(),'Y-m-d H:i:s'))
        //     ->when($r_date,function($q) use ($r_date){
        //         return $q->whereDate('date', $r_date);
        //     })->where('service_id',1)
        //     ->whereIn('status',[0,1,3])
        //     ->where('time_slot','!=','')->orderBy('date','asc')->pluck('time_slot')->toArray();

        $data = $data->get();

        $doctors = [];

        $vendorIds = VendorModel::where(['role'=>'3','deleted'=>'0'])->orderBy('id','desc')->pluck('id')->toArray();
        $holiday_dates = VendorHolidayDates::where([['date','>=',time_to_uae(now(),'Y-m-d')]])->whereIn('vendor_id',$vendorIds)->pluck('date')->toArray();

        foreach ($data as $key => $row) {
            // dd($row);
            $dates = [];
            $slots = [];
            $booked_time_slot_array = $row->service_quotes->pluck('time_slot')->toArray();
            foreach ($row->doctor_dates as $key => $val) {
                $DateTime = new \DateTime($val->date);
                $date = $DateTime->format('Y-m-d');
                // skip the date and slot for holiday dates
                if(in_array($date, $holiday_dates)){
                    continue;
                }
                $startTime = $val->start_time;
                $endTime = $val->end_time;
                $slots[] = $this->getTimeSlots($startTime, $endTime, $interval,$booked_time_slot_array);

                if(!in_array($date, $dates)){
                    array_push($dates, $date);
                }

            }
            $slots = call_user_func_array('array_merge', $slots);
            // $slots = $slots;
            // dd($slots);
            // $row->dates = $dates;
            // $row->slots = $slots;
            unset($row->doctor_dates);
            unset($row->service_quotes);
            if(count($slots) && in_array($request->time_slot, $slots) && !in_array(date('Y-m-d',strtotime($request->date)), $holiday_dates)){
                $doctors[] = $row;
            }
        }
        return response()->json([
            'status' => "1",
            'message' => trans('validation.data_fetched_successfully'),
            'errors' => [],
            'oData' => $doctors,
        ], 200);
    }

    public function appointment_types(Request $request)
    {
        $where['deleted'] = 0;
        $where['active'] = 1;

        $data = AppointmentTypes::select('id', 'name')->orderBy('name', 'asc')->where($where);
        if (isset($request->search_text) && $request->search_text) {
            $srch = $request->search_text;
            $data = $data->whereRaw("(name ilike '%$srch%')");
        }
        $data = $data->get();
        return response()->json([
            'status' => "1",
            'message' => trans('validation.data_fetched_successfully'),
            'errors' => [],
            'oData' => $data,
        ], 200);
    }

    public function groomers(Request $request)
    {
        $where['deleted'] = 0;
        $where['active'] = 1;
        $interval = $request->interval ?? 30;
        if(!$request->interval){
            if($request->pet_ids){
                $pets = MyPets::select('id', 'name', 'breed_id', 'sex', 'dob', 'weight', 'food', 'additional_notes', 'active', 'species as species_id', 'image', 'medicine_instructions')->with(['breed' => function ($q) {
                    $q->select('breeds.id', 'breeds.name', 'room_type_id as cage_type_id', 'room_types.name as cage_type', 'appoint_time_id', 'appointment_times.name as appointment_time', 'minutes_required as appointment_time_in_minutes')->leftjoin('room_types', 'room_types.id', 'room_type_id')->leftjoin('appointment_times', 'appointment_times.id', 'appoint_time_id');
                }])->whereIn('id', $request->pet_ids)->get();
                foreach ($pets as $pet) {
                    if (isset($pet->breed->appointment_time_in_minutes)) {
                        if ($pet->breed->appointment_time_in_minutes > $interval) {
                            $interval = $pet->breed->appointment_time_in_minutes;
                        }
                    }
                }
            }
        }


        $data = Groomers::orderBy('name', 'asc')->where($where);
        if (isset($request->search_text) && $request->search_text) {
            $srch = $request->search_text;
            $data = $data->whereRaw("(name ilike '%$srch%')");
        }
        if($request->date){
            $Date = $request->date;
            $date = Carbon::parse($Date)->format('Y-m-d');
            $data = $data->whereHas('groomer_dates', function($Query) use($date) {
                     return $Query->where('event_date', '>=',time_to_uae(now(),'Y-m-d H:i:s'))
                     ->whereDate('event_date', $date);
                 })
                ->with(['groomer_dates'=> function($Query) use($date) {
                     return $Query->where('event_date', '>=',time_to_uae(now(),'Y-m-d H:i:s'))
                     ->whereDate('event_date', $date)->orderBy('event_date','asc');
                 }])
                ->with(['service_quotes'=> function($Query) use($date) {
                     return $Query->where('date', '>=',time_to_uae(now(),'Y-m-d H:i:s'))
                     ->whereDate('date', $date)
                     ->where('service_id',2)
                     ->whereIn('status',[0,1,3])
                     ->where('time_slot','!=','')->orderBy('date','asc');
                 }])
            ;
        }

        // $booked_time_slot_array = ServiceQuotes::where('date', '>=',time_to_uae(now(),'Y-m-d H:i:s'))
        //     ->when($r_date,function($q) use ($r_date){
        //         return $q->whereDate('date', $r_date);
        //     })->where('service_id',1)
        //     ->whereIn('status',[0,1,3])
        //     ->where('time_slot','!=','')->orderBy('date','asc')->pluck('time_slot')->toArray();

        $data = $data->get();
        $vendorIds = VendorModel::where(['role'=>'3','deleted'=>'0'])->orderBy('id','desc')->pluck('id')->toArray();
        $holiday_dates = VendorHolidayDates::where([['date','>=',time_to_uae(now(),'Y-m-d')]])->whereIn('vendor_id',$vendorIds)->pluck('date')->toArray();
        

        $groomers = [];
        foreach ($data as $key => $row) {
            // dd($row);
            $dates = [];
            $slots = [];
            $booked_time_slot_array = $row->service_quotes->pluck('time_slot')->toArray();
            foreach ($row->groomer_dates as $key => $val) {

                $DateTime = new \DateTime($val->date);
                $date = $DateTime->format('Y-m-d');
                // skip the date and slot for holiday dates
                if(in_array($date, $holiday_dates)){
                    continue;
                }

                $startTime = $val->start_time;
                $endTime = $val->end_time;
                $slots[] = $this->getTimeSlots($startTime, $endTime, $interval,$booked_time_slot_array);

                $DateTime = new \DateTime($val->event_date);
                if(!in_array($date, $dates)){
                    array_push($dates, $date);
                }

            }
            $slots = call_user_func_array('array_merge', $slots);
            // $slots = $slots;
            // dd($slots);
            // $row->dates = $dates;
            // $row->slots = $slots;
            unset($row->groomer_dates);
            unset($row->service_quotes);
            if(count($slots) && in_array($request->time_slot, $slots) && !in_array(date('Y-m-d',strtotime($request->date)), $holiday_dates) ){
                $groomers[] = $row;
            }
        }
        return response()->json([
            'status' => "1",
            'message' => trans('validation.data_fetched_successfully'),
            'errors' => [],
            'oData' => $groomers,
        ], 200);
    }

    public function groomers_old(Request $request)
    {
        $where['deleted'] = 0;
        $where['active'] = 1;

        $data = Groomers::orderBy('name', 'asc')->where($where);
        if (isset($request->search_text) && $request->search_text) {
            $srch = $request->search_text;
            $data = $data->whereRaw("(name ilike '%$srch%')");
        }
        $data = $data->get();
        return response()->json([
            'status' => "1",
            'message' => trans('validation.data_fetched_successfully'),
            'errors' => [],
            'oData' => $data,
        ], 200);
    }

    public function doctor_timings(Request $request)
    {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];
        $rules = [
            'doctor_id' => 'required',
        ];
        $messages = [
            'doctor_id.required' => "Doctor ID required",
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $doctor_id = $request->doctor_id;
            $start_date = gmdate('Y-m-d');
            if (!$request->date) {
                $list = DoctorCalender::select('event_date as date', 'start_time', 'end_time')->where(['doctor_id' => $doctor_id])->where('event_date', '>=', $start_date)->orderBy('event_date', 'asc')->orderBy('start_time', 'asc')->get();
            } else {
                $list = DoctorCalender::select('event_date as date', 'start_time', 'end_time')->where(['doctor_id' => $doctor_id])->where('event_date', gmdate('Y-m-d', strtotime($request->date)))->orderBy('event_date', 'asc')->orderBy('start_time', 'asc')->get();
            }
            $o_data = convert_all_elements_to_string($list);
        }
        return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
    }

    public function groomer_timings(Request $request)
    {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];
        $rules = [
            'groomer_id' => 'required',
        ];
        $messages = [
            'groomer_id.required' => "Groomer ID required",
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $groomer_id = $request->groomer_id;
            $start_date = gmdate('Y-m-d');
            if (!$request->date) {
                $list = GroomerCalender::select('event_date as date', 'start_time', 'end_time')->where(['groomer_id' => $groomer_id])->where('event_date', '>=', $start_date)->orderBy('event_date', 'asc')->orderBy('start_time', 'asc')->get();
            } else {
                $list = GroomerCalender::select('event_date as date', 'start_time', 'end_time')->where(['groomer_id' => $groomer_id])->where('event_date', gmdate('Y-m-d', strtotime($request->date)))->orderBy('event_date', 'asc')->orderBy('start_time', 'asc')->get();
            }
            $o_data = convert_all_elements_to_string($list);
        }
        return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
    }

    public function grooming_types(Request $request)
    {
        $where['deleted'] = 0;
        $where['active'] = 1;

        $data = GroomingTypes::select('id', 'name')->orderBy('name', 'asc')->where($where);
        if (isset($request->search_text) && $request->search_text) {
            $srch = $request->search_text;
            $data = $data->whereRaw("(name ilike '%$srch%')");
        }
        $data = $data->get();
        return response()->json([
            'status' => "1",
            'message' => trans('validation.data_fetched_successfully'),
            'errors' => [],
            'oData' => $data,
        ], 200);
    }

    public function feeding_schedules(Request $request)
    {
        $where['deleted'] = 0;
        $where['active'] = 1;

        $data = FeedingSchedules::select('id', 'name')->orderBy('name', 'asc')->where($where);
        if (isset($request->search_text) && $request->search_text) {
            $srch = $request->search_text;
            $data = $data->whereRaw("(name ilike '%$srch%')");
        }
        $data = $data->get();
        return response()->json([
            'status' => "1",
            'message' => trans('validation.data_fetched_successfully'),
            'errors' => [],
            'oData' => $data,
        ], 200);
    }
    public function contact_us(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];

        $rules = [
            'name' => 'required',
            'email' => 'required',
            'dial_code' => 'required',
            'mobile' => 'required',
            'message' => 'required',

        ];
        $messages = [
            'name.required' => "Name required",
            'email.required' => "Email required",
            'dial_code.required' => "Dial Code required",
            'mobile.required' => "Mobile required",
            'message.required' => "Message required",
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $ins = [
                'name' => $request->name,
                'email' => $request->email,
                'dial_code' => $request->dial_code,
                'mobile' => $request->mobile,
                'message' => $request->message,
                'created_at' => gmdate('Y-m-d H:i:s'),
            ];

            if (ContactUs::create($ins)) {
                $status = "1";
                $message = "Successfully submitted.. We'll get back to you soon";
            } else {
                $message = "Something went wrong";
            }
        }
        return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
    }
    public function getTimeSlots($startTime, $endTime, $interval,$booked_time_slot_array=[],$range=true)
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);
        $slots = [];
        while ($start->lt($end)) {
            if($range){
                $time_slot = $start->format('h:i A').' - '.date('h:i A', strtotime(' +'.$interval.' minutes', strtotime($start)));
            }else{
                $time_slot = $start->format('h:i A');
            }
            if(!in_array($time_slot, $booked_time_slot_array)){
                $slots[] = $time_slot;
            }
            $start->addMinutes($interval);
        }
        return $slots;
    }
    public function doctor_timeslots(Request $request)
    {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];
        $rules = [
            // 'doctor_id' => 'required',
            // 'date' => 'required',
            // "pet_ids" => "required|array",
            // "pet_ids.*" => "required|distinct",

        ];
        $messages = [
            // 'doctor_id.required' => "Doctor ID required",
            // 'date.required' => "Date required",
            // 'pet_ids.required' => "Pet IDs required",
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $doctor_id = $request->doctor_id;
            $interval = 0;

            if($request->pet_ids){
                $pets = MyPets::select('id', 'name', 'breed_id', 'sex', 'dob', 'weight', 'food', 'additional_notes', 'active', 'species as species_id', 'image', 'medicine_instructions')->with(['breed' => function ($q) {
                    $q->select('breeds.id', 'breeds.name', 'room_type_id as cage_type_id', 'room_types.name as cage_type', 'appoint_time_id', 'appointment_times.name as appointment_time', 'minutes_required as appointment_time_in_minutes')->leftjoin('room_types', 'room_types.id', 'room_type_id')->leftjoin('appointment_times', 'appointment_times.id', 'appoint_time_id');
                }])->whereIn('id', $request->pet_ids)->get();
                foreach ($pets as $pet) {
                    if (isset($pet->breed->appointment_time_in_minutes)) {
                        if ($pet->breed->appointment_time_in_minutes > $interval) {
                            $interval = $pet->breed->appointment_time_in_minutes;
                        }
                    }
                }
            }
            if (!$interval) {
                $interval = 30;
            }
            $slots = [];
            $dates = [];
            $r_date =  $request->date ? date('Y-m-d',strtotime($request->date)) : '';

            $list = DoctorCalender::select('event_date as date', 'start_time', 'end_time')
            ->when($request->doctor_id,function($q) use ($request){
                return $q->where(['doctor_id' => $request->doctor_id]);
            })
            ->when($r_date,function($q) use ($r_date){
                return $q->where(['event_date' => $r_date]);
            })
            ->where('event_date', '>=',time_to_uae(now(),'Y-m-d H:i:s'))
            // ->where('event_date', gmdate('Y-m-d', strtotime($request->date)))
            ->groupBy('event_date', 'start_time', 'end_time')->orderBy('event_date', 'asc')->orderBy('start_time', 'asc')->get();

            $booked_time_slot_array = ServiceQuotes::where('date', '>=',time_to_uae(now(),'Y-m-d H:i:s'))
            ->when($r_date,function($q) use ($r_date){
                return $q->whereDate('date', $r_date);
            })->where('service_id',1)
            ->whereIn('status',[0,1,3])
            ->where('time_slot','!=','')->orderBy('date','asc')->pluck('time_slot')->toArray();


            $vendorIds = VendorModel::where(['role'=>'3','deleted'=>'0'])->orderBy('id','desc')->pluck('id')->toArray();
            
            $holiday_dates = VendorHolidayDates::where([['date','>=',time_to_uae(now(),'Y-m-d')]])->whereIn('vendor_id',$vendorIds)->pluck('date')->toArray();
            foreach ($list as $key => $val) {
                $DateTime = new \DateTime($val->date);
                $date = $DateTime->format('Y-m-d');
                // skip the date and slot for holiday dates
                if(in_array($date, $holiday_dates)){
                    continue;
                }
                $startTime = $val->start_time;
                $endTime = $val->end_time;
                $slots[] = $this->getTimeSlots($startTime, $endTime, $interval,$booked_time_slot_array);

                if(!in_array($date, $dates)){
                    array_push($dates, $date);
                }

            }
            $slots = call_user_func_array('array_merge', $slots);
            // $slots = $slots;
            $o_data['interval'] = $interval;
            $o_data['dates'] = $dates;
            $o_data['slots'] = $slots;
            $o_data = convert_all_elements_to_string($o_data);
        }
        
        return  response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' =>  (object) $o_data], 200);
    }

    public function groomer_timeslots_old(Request $request)
    {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];
        $rules = [
            'groomer_id' => 'required',
            'date' => 'required',
            "pet_ids" => "required|array",
            "pet_ids.*" => "required|distinct",

        ];
        $messages = [
            'groomer_id.required' => "Groomer ID required",
            'date.required' => "Date required",
            'pet_ids.required' => "Pet IDs required",
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $groomer_id = $request->groomer_id;

            $pets = MyPets::select('id', 'name', 'breed_id', 'sex', 'dob', 'weight', 'food', 'additional_notes', 'active', 'species as species_id', 'image', 'medicine_instructions')->with(['breed' => function ($q) {
                $q->select('breeds.id', 'breeds.name', 'room_type_id as cage_type_id', 'room_types.name as cage_type', 'appoint_time_id', 'appointment_times.name as appointment_time', 'minutes_required as appointment_time_in_minutes')->leftjoin('room_types', 'room_types.id', 'room_type_id')->leftjoin('appointment_times', 'appointment_times.id', 'appoint_time_id');
            }])->whereIn('id', $request->pet_ids)->get();
            $interval = 0;
            foreach ($pets as $pet) {
                if (isset($pet->breed->appointment_time_in_minutes)) {
                    if ($pet->breed->appointment_time_in_minutes > $interval) {
                        $interval = $pet->breed->appointment_time_in_minutes;
                    }
                }
            }
            if (!$interval) {
                $interval = 30;
            }
            $slots = [];
            $list = GroomerCalender::select('event_date as date', 'start_time', 'end_time')->where(['groomer_id' => $groomer_id])->where('event_date', gmdate('Y-m-d', strtotime($request->date)))->orderBy('event_date', 'asc')->orderBy('start_time', 'asc')->get();
            foreach ($list as $key => $val) {
                $startTime = $val->start_time;
                $endTime = $val->end_time;
                $slots[] = $this->getTimeSlots($startTime, $endTime, $interval);
            }
            $slots = call_user_func_array('array_merge', $slots);
            // $slots = $slots;
            $o_data['list'] = $list;
            $o_data['slots'] = $slots;
            $o_data = convert_all_elements_to_string($o_data);
        }
        
        return  response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' =>  (object) $o_data], 200);
    }

    public function groomer_timeslots(Request $request)
    {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];
        $rules = [
            // 'groomer_id' => 'required',
            // 'date' => 'required',
            // "pet_ids" => "required|array",
            // "pet_ids.*" => "required|distinct",

        ];
        $messages = [
            // 'groomer_id.required' => "Doctor ID required",
            // 'date.required' => "Date required",
            // 'pet_ids.required' => "Pet IDs required",
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $groomer_id = $request->groomer_id;
            $interval = 0;

            if($request->pet_ids){
                $pets = MyPets::select('id', 'name', 'breed_id', 'sex', 'dob', 'weight', 'food', 'additional_notes', 'active', 'species as species_id', 'image', 'medicine_instructions')->with(['breed' => function ($q) {
                    $q->select('breeds.id', 'breeds.name', 'room_type_id as cage_type_id', 'room_types.name as cage_type', 'appoint_time_id', 'appointment_times.name as appointment_time', 'minutes_required as appointment_time_in_minutes')->leftjoin('room_types', 'room_types.id', 'room_type_id')->leftjoin('appointment_times', 'appointment_times.id', 'appoint_time_id');
                }])->whereIn('id', $request->pet_ids)->get();
                foreach ($pets as $pet) {
                    if (isset($pet->breed->appointment_time_in_minutes)) {
                        if ($pet->breed->appointment_time_in_minutes > $interval) {
                            $interval = $pet->breed->appointment_time_in_minutes;
                        }
                    }
                }
            }
            if (!$interval) {
                $interval = 30;
            }
            $slots = [];
            $dates = [];
            $r_date =  $request->date ? date('Y-m-d',strtotime($request->date)) : '';

            $list = GroomerCalender::select('event_date as date', 'start_time', 'end_time')
            ->when($request->groomer_id,function($q) use ($request){
                return $q->where(['groomer_id' => $request->groomer_id]);
            })
            ->when($r_date,function($q) use ($r_date){
                return $q->where(['event_date' => $r_date]);
            })
            ->where('event_date', '>=',time_to_uae(now(),'Y-m-d H:i:s'))
            // ->where('event_date', gmdate('Y-m-d', strtotime($request->date)))
            ->groupBy('event_date', 'start_time', 'end_time')->orderBy('event_date', 'asc')->orderBy('start_time', 'asc')->get();

            $booked_time_slot_array = ServiceQuotes::where('date', '>=',time_to_uae(now(),'Y-m-d H:i:s'))
            ->when($r_date,function($q) use ($r_date){
                return $q->whereDate('date', $r_date);
            })->where('service_id',2)
            ->whereIn('status',[0,1,3])
            ->where('time_slot','!=','')->orderBy('date','asc')->pluck('time_slot')->toArray();

            $vendorIds = VendorModel::where(['role'=>'3','deleted'=>'0'])->orderBy('id','desc')->pluck('id')->toArray();
            $holiday_dates = VendorHolidayDates::where([['date','>=',time_to_uae(now(),'Y-m-d')]])->whereIn('vendor_id',$vendorIds)->pluck('date')->toArray();

            foreach ($list as $key => $val) {
                $DateTime = new \DateTime($val->date);
                $date = $DateTime->format('Y-m-d');
                // skip the date and slot for holiday dates
                if(in_array($date, $holiday_dates)){
                    continue;
                }
                $startTime = $val->start_time;
                $endTime = $val->end_time;
                $slots[] = $this->getTimeSlots($startTime, $endTime, $interval,$booked_time_slot_array);

                if(!in_array($date, $dates)){
                    array_push($dates, $date);
                }
            }
            $slots = call_user_func_array('array_merge', $slots);
            // $slots = $slots;
            $o_data['interval'] = $interval;
            $o_data['dates'] = $dates;
            $o_data['slots'] = $slots;
            $o_data = convert_all_elements_to_string($o_data);
        }
        
        return  response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' =>  (object) $o_data], 200);
    }

    public function doctor_dates(Request $request)
    {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];
        $rules = [

        ];
        $messages = [
            
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            
            $slots = [];
            $dates = [];
            $r_date =  $request->date ? date('Y-m-d',strtotime($request->date)) : '';

            $list = DoctorCalender::when($request->doctor_id,function($q) use ($request){
                return $q->where(['doctor_id' => $request->doctor_id]);
            })->when($r_date,function($q) use ($r_date){
                return $q->where(['event_date' =>$r_date]);
            })->where('event_date', '>=',time_to_uae(now(),'Y-m-d H:i:s'))->select('event_date as date', 'start_time', 'end_time')
            // ->groupBy('event_date', 'start_time', 'end_time')
            ->orderBy('start_time', 'asc')
            ->orderBy('event_date', 'asc')
            ->get();

            $vendorIds = VendorModel::where(['role'=>'3','deleted'=>'0'])->orderBy('id','desc')->pluck('id')->toArray();
            $holiday_dates = VendorHolidayDates::where([['date','>=',time_to_uae(now(),'Y-m-d')]])->whereIn('vendor_id',$vendorIds)->pluck('date')->toArray();

            foreach ($list as $key => $val) {

                $DateTime = new \DateTime($val->date);
                $date = $DateTime->format('Y-m-d');
                // skip the date and slot for holiday dates
                if(in_array($date, $holiday_dates)){
                    continue;
                }

                if(!in_array($date, $dates)){
                    array_push($dates, $date);
                }
                $start_time = new \DateTime($val->start_time);
                $end_time = new \DateTime($val->end_time);

                $slot = $start_time->format('h:i A').' - '.$end_time->format('h:i A');
                if(!in_array($slot, $slots)){
                    array_push($slots, $slot);
                }

            }
            $o_data['dates'] = $dates;
            $o_data['slots'] = $slots;
            $o_data = convert_all_elements_to_string($o_data);
        }
        
        return  response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' =>  (object) $o_data], 200);
    }
    public function groomer_dates(Request $request)
    {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];
        $rules = [

        ];
        $messages = [
            
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            
            $slots = [];
            $dates = [];
            $list = GroomerCalender::where('event_date', '>=',time_to_uae(now(),'Y-m-d H:i:s'))->select('event_date as date', 'start_time', 'end_time')
            // ->groupBy('event_date', 'start_time', 'end_time')
            ->orderBy('start_time', 'asc')
            ->orderBy('event_date', 'asc')
            ->get();

            $vendorIds = VendorModel::where(['role'=>'3','deleted'=>'0'])->orderBy('id','desc')->pluck('id')->toArray();
            $holiday_dates = VendorHolidayDates::where([['date','>=',time_to_uae(now(),'Y-m-d')]])->whereIn('vendor_id',$vendorIds)->pluck('date')->toArray();


            foreach ($list as $key => $val) {
                $DateTime = new \DateTime($val->date);
                $date = $DateTime->format('Y-m-d');
                // skip the date and slot for holiday dates
                if(in_array($date, $holiday_dates)){
                    continue;
                }
                if(!in_array($date, $dates)){
                    array_push($dates, $date);
                }
                $start_time = new \DateTime($val->start_time);
                $end_time = new \DateTime($val->end_time);

                $slot = $start_time->format('h:i A').' - '.$end_time->format('h:i A');
                if(!in_array($slot, $slots)){
                    array_push($slots, $slot);
                }

            }
            $o_data['dates'] = $dates;
            $o_data['slots'] = $slots;
            $o_data = convert_all_elements_to_string($o_data);
        }
        
        return  response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' =>  (object) $o_data], 200);
    }

    public function vendor_availability(Request $request)
    {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];

        $rules = [
            'service_id' => 'required',
            "pet_ids" => "required|array",
            'date' => 'required',

        ];
        $messages = [
            'service_id.required' => "Doctor ID required",
            'pet_ids.required' => "Pet is required",
            'date.required' => "Date required",
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
            return  response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' =>  (object) $o_data], 200);
        }

        $where['deleted'] = 0;
        $where['active'] = 1;
        $interval = $request->interval ?? 30;
        if(!$request->interval){
            if($request->pet_ids){
                $pets = MyPets::select('id', 'name', 'breed_id', 'sex', 'dob', 'weight', 'food', 'additional_notes', 'active', 'species as species_id', 'image', 'medicine_instructions')->with(['breed' => function ($q) {
                    $q->select('breeds.id', 'breeds.name', 'room_type_id as cage_type_id', 'room_types.name as cage_type', 'appoint_time_id', 'appointment_times.name as appointment_time', 'minutes_required as appointment_time_in_minutes')->leftjoin('room_types', 'room_types.id', 'room_type_id')->leftjoin('appointment_times', 'appointment_times.id', 'appoint_time_id');
                }])->whereIn('id', $request->pet_ids)->get();
                foreach ($pets as $pet) {
                    if (isset($pet->breed->appointment_time_in_minutes)) {
                        if ($pet->breed->appointment_time_in_minutes > $interval) {
                            $interval = $pet->breed->appointment_time_in_minutes;
                        }
                    }
                }
            }
        }


        $data = VendorModel::select('*','users.name as name','users.created_at as u_created_at','industry_types.name as industry','users.active as active','users.id as id')
        ->where(['role'=>'3','users.deleted'=>'0'])
        ->leftjoin('vendor_details','vendor_details.user_id','=','users.id')
        ->leftjoin('industry_types','industry_types.id','=','vendor_details.industry_type')
        ->orderBy('users.id','desc')->get();

       
        $r_date =  $request->date ? date('Y-m-d',strtotime($request->date)) : date('Y-m-d',strtotime(now()));
        $day = strtolower(date('l',strtotime($r_date)));

        $vendorIds = $data->pluck('id')->toArray();
        $holiday_dates = VendorHolidayDates::where([['date','>=',time_to_uae(now(),'Y-m-d')]])->whereIn('vendor_id',$vendorIds)->pluck('date')->toArray();
        
        $slots = [];
        $days = [];
        foreach ($data as $key => $row) {

            $startTime = '';
            $endTime ='';
            // get availablity as per the day service and vendor
            $boarding_availablity =VendorServiceTimings::where(['service_id'=>$request->service_id,'vendor'=>$row->id,strtolower($day)=>1])->first();
            if($boarding_availablity){
                if($day == 'sunday'){
                    $startTime = $boarding_availablity->sun_from;
                    $endTime = $boarding_availablity->sun_to;
                }
                if($day == 'monday'){
                    $startTime = $boarding_availablity->mon_from;
                    $endTime = $boarding_availablity->mon_to;
                }
                if($day == 'tuesday'){
                    $startTime = $boarding_availablity->tues_from;
                    $endTime = $boarding_availablity->tues_to;
                }
                if($day == 'wednesday'){
                    $startTime = $boarding_availablity->wed_from;
                    $endTime = $boarding_availablity->wed_to;
                }
                if($day == 'thursday'){
                    $startTime = $boarding_availablity->thurs_from;
                    $endTime = $boarding_availablity->thurs_to;
                }
                if($day == 'friday'){
                    $startTime = $boarding_availablity->fri_from;
                    $endTime = $boarding_availablity->fri_to;
                }
                if($day == 'saturday'){
                    $startTime = $boarding_availablity->sat_from;
                    $endTime = $boarding_availablity->sat_to;
                }
                $days[] = $day;
                $range = false;
                if($startTime && !in_array($r_date, $holiday_dates)){
                    $slots[] = $this->getTimeSlots($startTime, $endTime, $interval,[],$range);
                }
            }
            $slots = call_user_func_array('array_merge', $slots);
        }

        $o_data['holidays'] = $holiday_dates;
        $o_data['day'] = $days;
        $o_data['slots'] = $slots;

        return response()->json([
            'status' => "1",
            'message' => trans('validation.data_fetched_successfully'),
            'errors' => [],
            'oData' => (object)$o_data,
        ], 200);
    }
}
