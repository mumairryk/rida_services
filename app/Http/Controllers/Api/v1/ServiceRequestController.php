<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\ServiceFoods;
use App\Models\ServicePets;
use App\Models\ServiceQuotes;
use App\Models\User;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use App\Models\DoctorCalender;
use App\Models\GroomerCalender;
use Validator;
use App\Models\DoggyPlayTimeDates;
use App\Models\DoggyPlayTimeTempBooking;

class ServiceRequestController extends Controller
{
    //
    public function __construct(Database $database)
    {
        $this->database = $database;
    }
    private function getUserId($access_token)
    {
        $user_id = 0;
        $user = User::where(['user_access_token' => $access_token])->where('user_access_token', '!=', '')->get();
        if ($user->count() > 0) {
            $user_id = $user->first()->id;
        }
        return $user_id;
    }
    private function validateAccesToken($access_token)
    {

        $user = User::where(['user_access_token' => $access_token])->get();

        if ($user->count() == 0) {
            http_response_code(401);
            echo json_encode([
                'status' => "0",
                'message' => trans('validation.invalid_login'),
                'oData' => [],
                'errors' => (object) [],
            ]);
            exit;

        } else {
            $user = $user->first();
            if ($user->active == 1) {
                return $user->id;
            } else {
                http_response_code(401);
                echo json_encode([
                    'status' => "0",
                    'message' => trans('validation.invalid_login'),
                    'oData' => [],
                    'errors' => (object) [],
                ]);
                exit;
                return response()->json([
                    'status' => "0",
                    'message' => trans('validation.invalid_login'),
                    'oData' => [],
                    'errors' => (object) [],
                ], 401);
                exit;
            }
        }
    }
    public function validateDoggyDates(Request $request){
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];

        $service_id = 5;
        $r_date = date('Y-m-d', strtotime($request->date));

        $dpt_date = DoggyPlayTimeDates::whereDate('date',  $r_date)
        // ->where('vendor_id', $ID)
        ->whereDate('date', '>=',time_to_uae(now(),'Y-m-d H:i:s'))
        ->orderBy('date', 'ASC')
        ->first();

        if(!$dpt_date){
            $message = "Date is not avaiable";
            return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
        }

        $filled = ServiceQuotes::where('date', '>=',time_to_uae(now(),'Y-m-d H:i:s'))
        // ->select('slots')
        ->where('service_id', $service_id)
        ->whereIn('status',[0,1,3])
        ->whereDate('date',  $r_date)
        ->sum('seats');
        $available_slots = (($dpt_date->total_seats ?? 0) - $filled);
        $filled = abs($filled);
       
        if($request->seats >  $available_slots){
            $message = "Selected Slots are not avaiable.";
            return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
        }
        $status = '1';
        return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);

    }


    public function checkout_doggy_playtime(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];

        $rules = [
            'access_token' => 'required',
            'date' => 'required',
            'seats' => 'required',
            "pet_ids" => "required|array",
        ];
        $messages = [
            'access_token.required' => "Access token required",
            'date.required' => "Date required",
            'seats.required' => "Select Seats",

        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $service_id = 5;

            $r_date = date('Y-m-d', strtotime($request->date));

            $dpt_date = DoggyPlayTimeDates::whereDate('date',  $r_date)
            // ->where('vendor_id', $ID)
            ->whereDate('date', '>=',time_to_uae(now(),'Y-m-d H:i:s'))
            ->orderBy('date', 'ASC')
            ->first();

            $validate_messages = $this->validateDoggyDates($request);
            $validate_messages = $validate_messages->original;

            if($validate_messages['status'] == '0'){
                return $validate_messages;
            }

            $user_id = $this->validateAccesToken($request->access_token);

            $price = ($dpt_date->price * $request->seats);
            $ins = [
                'user_id' => $user_id,
                'service_id' => $service_id,
                'pet_id' => 0, //$request->pet_id,
                'pet_ids' => $request->pet_ids,
                'seats' => $request->seats,
                'total' => $price,
                'vat' => 0,
                'discount' => 0,
                'paid_price' => $price,
                'grand_total' => $price,
                'date' => $r_date,
                'status' => 0, //means pending
                'created_at' => gmdate('Y-m-d H:i:s'),
            ];

            $temp_order = new DoggyPlayTimeTempBooking();
            $temp_order->user_id = $user_id;
            $temp_order->service_id = $service_id;
            $temp_order->total = $price;
            $temp_order->request_data = json_encode($ins);
            
            $temp_order->save();
            if($dpt_date->price == 0){
                $o_data['invoice_id'] = ($temp_order->id);
                $o_data['payment_ref'] = '';
                $status = "1";
                $o_data = convert_all_elements_to_string($sq);
                return response()->json(['status' => $status, 'message' => $message, 'errors' => $errors, 'oData' => $o_data]);

            }
            
            $stripe = new \App\Http\Controllers\Api\v1\CartController(app('firebase.database'),$request);
            $o_data = $stripe->payment_init_stripe($temp_order->id,$temp_order->id, $price, 0, $user_id, $request->address_id, 0);

            $temp_order->payment_id =  $o_data['payment_ref'] ?? '';
            $temp_order->save();

            $status = "1";
            $o_data = convert_all_elements_to_string($o_data);
            return response()->json(['status' => $status, 'message' => $message, 'errors' => $errors, 'oData' => $o_data]);


            if ($sq = ServiceQuotes::create($ins)) {
                // $o_data['service_quotes'] = $sq;
                foreach ($request->pet_ids as $pet) {
                    $sp['service_id'] = $sq->id;
                    $sp['pet_id'] = $pet;
                    $sp = ServicePets::create($sp);
                    // $o_data['service_pets'] = $sp;

                }

                $user = User::find($user_id);
                $title = "Booking Placed Successfully";
                $description = "Your Doggy Play time service slot/s has been booked successfully";
                $ntype = 'veterinary_service_booked';
                $record_id = $sq->id;

                if (isset($user->user_device_token)) {
                    prepare_notification($this->database, $user, $title, $description, $ntype, $record_id, $ntype, null);
                }

                $status = "1";
                $message = "Booking Placed Successfully";
                $o_data = convert_all_elements_to_string($sq);
            } else {
                $message = "Something went wrong";
            }
        }
        return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
    }

    
    public function verify_booking_doggy_playtime(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];

        $rules = [
            'access_token' => 'required',
            'invoice_id' => 'required',
        ];
        $messages = [
            'access_token.required' => "Access token required",
            'invoice_id.required' => "invoice id is required",

        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {

            $temp_order = DoggyPlayTimeTempBooking::find($request->invoice_id);
            if(!$temp_order){
                $message = "Unable to verify booking";
                return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
            }
            $request_d   = json_decode($temp_order->request_data,true);
            $request->merge($request_d);

            $validate_messages = $this->validateDoggyDates($request);
            $validate_messages = $validate_messages->original;

            if($validate_messages['status'] == '0'){
                return $validate_messages;
            }

            $user_id = $this->validateAccesToken($request->access_token);
            unset($request_d['pet_ids']);
            $ins = $request_d;
            if ($sq = ServiceQuotes::create($ins)) {
                // $o_data['service_quotes'] = $sq;
                foreach ($request->pet_ids as $pet) {
                    $sp['service_id'] = $sq->id;
                    $sp['pet_id'] = $pet;
                    $sp = ServicePets::create($sp);
                    // $o_data['service_pets'] = $sp;

                }

                $user = User::find($user_id);
                $title = "Booking Placed Successfully";
                $description = "Your Doggy Play time service slot/s has been booked successfully";
                $ntype = 'veterinary_service_booked';
                $record_id = $sq->id;

                if (isset($user->user_device_token)) {
                    prepare_notification($this->database, $user, $title, $description, $ntype, $record_id, $ntype, null);
                }

                $status = "1";
                $message = "Booking Placed Successfully";
                $o_data = convert_all_elements_to_string($sq);
            } else {
                $message = "Something went wrong";
            }
        }
        return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
    }


    public function book_veterinary_service(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];

        $rules = [
            'access_token' => 'required',
            // 'pet_id' => 'required',
            'doctor_id' => 'required',
            'appointment_type' => 'required',
            'time' => 'required',
            'date' => 'required',
            "pet_ids" => "required|array",
            "pet_ids.*" => "required|distinct",
        ];
        $messages = [
            'access_token.required' => "Access token required",
            // 'pet_id.required' => "Pet required",
            'doctor_id.required' => "Doctor required",
            'appointment_type.required' => "Appointment Type required",
            'time.required' => "Time required",
            'date.required' => "Date required",
            'pet_ids.required' => "Pet IDs required",

        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $r_date = date('Y-m-d', strtotime($request->date));
            $gdate = DoctorCalender::select('event_date as date', 'start_time', 'end_time')
            ->when($request->doctor_id,function($q) use ($request){
                return $q->where(['doctor_id' => $request->doctor_id]);
            })
            ->when($r_date,function($q) use ($r_date){
                return $q->where(['event_date' => $r_date]);
            })
            ->where('event_date', '>=',time_to_uae(now(),'Y-m-d H:i:s'))
            ->groupBy('event_date', 'start_time', 'end_time')->orderBy('event_date', 'asc')->orderBy('start_time', 'asc')->first();
            if(!$gdate){
                $message = "Doctor is not avaiable in the selected date";
                return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);

            }

            // check the time slot have booking or not
            $booked_time_slot = ServiceQuotes::where('date', '>=',time_to_uae(now(),'Y-m-d H:i:s'))
            ->whereDate('date', $r_date)
            ->where('service_id',1)
            ->where('doctor_id',$request->doctor_id)
            ->whereIn('status',[0,1,3])
            ->where('time_slot',$request->time)->orderBy('date','asc')->count();

            if($booked_time_slot){
                $message = "Doctor has already booking on seleted date and time";
                return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
            }
            $user_id = $this->validateAccesToken($request->access_token);
            $service_time = explode(' - ', $request->time)[0];
            $ins = [
                'user_id' => $user_id,
                'service_id' => 1,
                'pet_id' => 0, //$request->pet_id,
                'doctor_id' => $request->doctor_id,
                'appointment_type' => $request->appointment_type,
                'time' => $service_time ? date('H:i:s', strtotime($service_time)) : '',
                'time_slot' => $request->time,
                'date' => $r_date,
                'status' => 0, //means pending
                'created_at' => gmdate('Y-m-d H:i:s'),
            ];
            if ($sq = ServiceQuotes::create($ins)) {
                // $o_data['service_quotes'] = $sq;
                foreach ($request->pet_ids as $pet) {
                    $sp['service_id'] = $sq->id;
                    $sp['pet_id'] = $pet;
                    $sp = ServicePets::create($sp);
                    // $o_data['service_pets'] = $sp;

                }

                $user = User::find($user_id);
                $title = "Booking Placed Successfully";
                $description = "Your Veterinary Service Booked Successfully";
                $ntype = 'veterinary_service_booked';
                $record_id = $sq->id;

                if (isset($user->user_device_token)) {
                    prepare_notification($this->database, $user, $title, $description, $ntype, $record_id, $ntype, null);
                }

                $status = "1";
                $message = "Booking Placed Successfully";
            } else {
                $message = "Something went wrong";
            }
        }
        return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
    }

    public function book_grooming_service(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];

        $rules = [
            'access_token' => 'required',
            // 'pet_id' => 'required',
            'groomer_id' => 'required',
            'grooming_service' => 'required',
            'time' => 'required',
            'date' => 'required',
        ];
        $messages = [
            'access_token.required' => "Access token required",
            // 'pet_id.required' => "Pet required",
            'groomer_id.required' => "Groomer required",
            'grooming_service.required' => "Grooming Type required",
            'time.required' => "Time required",
            'date.required' => "Date required",
            'pet_ids.required' => "Pet IDs required",

        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $user_id = $this->validateAccesToken($request->access_token);

            $r_date = date('Y-m-d', strtotime($request->date));
            // check the date in exist or not
            $gdate = GroomerCalender::select('event_date as date', 'start_time', 'end_time')
            ->when($request->groomer_id,function($q) use ($request){
                return $q->where(['groomer_id' => $request->groomer_id]);
            })
            ->when($r_date,function($q) use ($r_date){
                return $q->where(['event_date' => $r_date]);
            })
            ->where('event_date', '>=',time_to_uae(now(),'Y-m-d H:i:s'))
            ->groupBy('event_date', 'start_time', 'end_time')->orderBy('event_date', 'asc')->orderBy('start_time', 'asc')->first();
            if(!$gdate){
                $message = "Groomer is not avaiable in the selected date";
                return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
            }
            // check the time slot have booking or not
            $booked_time_slot = ServiceQuotes::where('date', '>=',time_to_uae(now(),'Y-m-d H:i:s'))
            ->whereDate('date', $r_date)
            ->where('service_id',2)
            ->where('groomer_id',$request->groomer_id)
            ->whereIn('status',[0,1,3])
            ->where('time_slot',$request->time)->orderBy('date','asc')->count();

            if($booked_time_slot){
                $message = "Groomer has already booking on seleted date and time";
                return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
            }

            $service_time = explode(' - ', $request->time)[0];
            $ins = [
                'user_id' => $user_id,
                'service_id' => 2,
                'pet_id' => 0, //$request->pet_id,
                'groomer_id' => $request->groomer_id,
                'grooming_service' => $request->grooming_service,
                'time' => $service_time ? date('H:i:s', strtotime($service_time)) : '',
                'time_slot' => $request->time,
                'date' => date('Y-m-d', strtotime($request->date)),
                'status' => 0, //means pending status
                'created_at' => gmdate('Y-m-d H:i:s'),
            ];

            if ($sq = ServiceQuotes::create($ins)) {

                foreach ($request->pet_ids as $pet) {
                    $sp['service_id'] = $sq->id;
                    $sp['pet_id'] = $pet;
                    ServicePets::create($sp);
                }

                $user = User::find($user_id);
                $title = "Booking Placed Successfully";
                $description = "Your Grooming Service Booked Successfully";
                $ntype = 'grooming_service_booked';
                $record_id = $sq->id;

                if (isset($user->user_device_token)) {
                    prepare_notification($this->database, $user, $title, $description, $ntype, $record_id, $ntype, null);
                }

                $status = "1";
                $message = "Booking Placed Successfully";
            } else {
                $message = "Something went wrong";
            }
        }
        return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
    }

    public function book_boarding_service(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];

        $rules = [
            'access_token' => 'required',
            // 'pet_id' => 'required',
            'drop_off_time' => 'required',
            'drop_off_date' => 'required',
            'pick_up_time' => 'required',
            'pick_up_date' => 'required',
            // 'feeding_schedule' => 'required',
            // 'food_id' => 'required',
            "pet_ids" => "required|array",
            "pet_ids.*" => "required|distinct",
        ];
        $messages = [
            'access_token.required' => "Access token required",
            'pet_id.required' => "Pet required",
            'drop_off_time.required' => "Drop-off time required",
            'drop_off_date.required' => "Drop-off date required",
            'pick_up_time.required' => "Pick-up time required",
            'pick_up_date.required' => "Pick-up date required",
            // 'feeding_schedule.required' => "Feeding schedule required",
            // 'food_id.required' => "Food required",
            'pet_ids.required' => "Pet IDs required",

        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $user_id = $this->validateAccesToken($request->access_token);
            $drop_off_date = date('Y-m-d', strtotime($request->drop_off_date));
            $pick_up_date = date('Y-m-d', strtotime($request->pick_up_date));
            if(!($drop_off_date < $pick_up_date)){
                $message = "Pick-up date cannot be earlier than Drop-off";
                return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
            }                
            $ins = [
                'user_id' => $user_id,
                'service_id' => 3,
                'pet_id' => 0, //$request->pet_id,
                'drop_off_time' => $request->drop_off_time ? date('H:i:s', strtotime($request->drop_off_time)) : '',
                'drop_off_date' => $drop_off_date,
                'pick_up_time' => $request->pick_up_time ? date('H:i:s', strtotime($request->pick_up_time)) : '',
                'pick_up_date' => $pick_up_date,
                'food_id' => (isset($request->food_id) && $request->food_id) ? $request->food_id : 0,
                'room_id' => (isset($request->room_id) && $request->room_id) ? $request->room_id : 0,
                'food' => isset($request->food) ? $request->food : '',
                'feeding_schedule' => $request->feeding_schedule,
                'specific_medication' => $request->specific_medication_needed ?? 0,
                'medicine_instructions' => isset($request->medicine_instructions) ? $request->medicine_instructions : '',
                'notes' => isset($request->notes) ? $request->notes : '',
                'status' => 0,
                'created_at' => gmdate('Y-m-d H:i:s'),
            ];

            if ($sq = ServiceQuotes::create($ins)) {
                foreach ($request->pet_ids as $pet) {
                    $sp['service_id'] = $sq->id;
                    $sp['pet_id'] = $pet;
                    ServicePets::create($sp);
                }

                if (isset($request->food_ids)) {
                    foreach ($request->food_ids as $food) {
                        if ($food) {
                            $sf['service_id'] = $sq->id;
                            $sf['food_id'] = $food;
                            ServiceFoods::create($sf);
                        }
                    }
                }

                $user = User::find($user_id);
                $title = "Booking Placed Successfully";
                $description = "Your Boarding Service Booked Successfully";
                $notification_id = time();
                $ntype = 'boarding_service_booked';
                $record_id = $sq->id;

                if (isset($user->user_device_token)) {
                    prepare_notification($this->database, $user, $title, $description, $ntype, $record_id, $ntype, null);
                }

                $status = "1";
                $message = "Booking Placed Successfully";
            } else {
                $message = "Something went wrong";
            }
        }
        return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
    }

    public function day_care_reservation(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];

        $rules = [
            'access_token' => 'required',
            // 'pet_id' => 'required',
            'drop_off_time' => 'required',
            'drop_off_date' => 'required',
            'pick_up_time' => 'required',
            'pick_up_date' => 'required',
            // 'feeding_schedule' => 'required',
            // 'food_id' => 'required',
            "pet_ids" => "required|array",
            "pet_ids.*" => "required|distinct",
        ];
        $messages = [
            'access_token.required' => "Access token required",
            // 'pet_id.required' => "Pet required",
            'drop_off_time.required' => "Drop-off time required",
            'drop_off_date.required' => "Drop-off date required",
            'pick_up_time.required' => "Pick-up time required",
            'pick_up_date.required' => "Pick-up date required",
            // 'feeding_schedule.required' => "Feeding schedule required",
            // 'food_id.required' => "Food required",
            'pet_ids.required' => "Pet IDs required",

        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $user_id = $this->validateAccesToken($request->access_token);
            $drop_off_date = date('Y-m-d', strtotime($request->drop_off_date));
            $pick_up_date = date('Y-m-d', strtotime($request->pick_up_date));
            if(($drop_off_date != $pick_up_date)){
                $message = "Pick-up and Drop-off date need to be same.";
                return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
            }  

            $ins = [
                'user_id' => $user_id,
                'service_id' => 4,
                'pet_id' => 0, //$request->pet_id,
                'drop_off_time' => $request->drop_off_time ? date('H:i:s', strtotime($request->drop_off_time)) : '',
                'drop_off_date' => $drop_off_date,
                'pick_up_time' => $request->pick_up_time ? date('H:i:s', strtotime($request->pick_up_time)) : '',
                'pick_up_date' => $pick_up_date,
                'food_id' => (isset($request->food_id) && $request->food_id) ? $request->food_id : 0,
                'room_id' => (isset($request->room_id) && $request->room_id) ? $request->room_id : 0,
                'food' => isset($request->food) ? $request->food : '',
                'feeding_schedule' => $request->feeding_schedule,
                'specific_medication' => $request->specific_medication_needed ?? 0,
                'medicine_instructions' => isset($request->medicine_instructions) ? $request->medicine_instructions : '',
                'notes' => isset($request->notes) ? $request->notes : '',
                'status' => 0,
                'created_at' => gmdate('Y-m-d H:i:s'),
            ];

            if ($sq = ServiceQuotes::create($ins)) {
                foreach ($request->pet_ids as $pet) {
                    $sp['service_id'] = $sq->id;
                    $sp['pet_id'] = $pet;
                    ServicePets::create($sp);
                }

                if (isset($request->food_ids)) {
                    foreach ($request->food_ids as $food) {
                        if ($food) {
                            $sf['service_id'] = $sq->id;
                            $sf['food_id'] = $food;
                            ServiceFoods::create($sf);
                        }
                    }
                }

                $user = User::find($user_id);
                $title = "Booking Placed Successfully";
                $description = "Your Day Care Service Booked Successfully";
                $notification_id = time();
                $ntype = 'daycare_service_booked';
                $record_id = $sq->id;
                
                if (isset($user->user_device_token)) {
                    prepare_notification($this->database, $user, $title, $description, $ntype, $record_id, $ntype, null);
                }


                $status = "1";
                $message = "Booking Placed Successfully";
            } else {
                $message = "Something went wrong";
            }
        }
        return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
    }

    public function my_bookings(REQUEST $request)
    {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];

        $rules = [
            'access_token' => 'required',

        ];
        $messages = [
            'access_token.required' => "Access token required",
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $user_id = $this->validateAccesToken($request->access_token);
            $limit = $request->limit ?? 20;
            $page = $request->page ?? 1;
            $offset = ($page - 1) * $limit;
            $list = ServiceQuotes::select('service_quotes.id', 'service_quotes.service_id', 'service_quotes.created_at', 'service_quotes.date', 'service_quotes.time', 'service_quotes.drop_off_date', 'service_quotes.drop_off_time', 'services.name as service','status')->join('services', 'services.id', 'service_quotes.service_id')->where('user_id', $user_id)->orderBy('service_quotes.id', 'desc')->skip($offset)->take($limit)->get();

            foreach ($list as $key => $val) {
                $list[$key]->status_text = service_status($val->status);
                $list[$key]->booking_number = '#' . config('global.quote_prefix') . date(date('Ymd', strtotime($val->created_at))) . $val->id;

                if ($val->service_id == 1 || $val->service_id == 2) {
                    $list[$key]->scheduled_date = date('d F Y - h:i A', strtotime($val->date . ' ' . $val->time));
                }
                if ($val->service_id == 3 || $val->service_id == 4) {
                    $list[$key]->scheduled_date = date('d F Y - h:i A', strtotime($val->drop_off_date . ' ' . $val->drop_off_time));
                }
                if ($request->timezone) {
                    $list[$key]->booking_date = api_date_in_timezone($val->created_at, 'd F Y - h:i A', $request->timezone);
                } else {
                    $list[$key]->booking_date = date('d F Y - h:i A', strtotime($val->created_at));
                }
                unset($list[$key]->created_at, $list[$key]->date, $list[$key]->time, $list[$key]->drop_off_date, $list[$key]->drop_off_time, $list[$key]->service_id, $list[$key]->status);
            }
            $o_data = convert_all_elements_to_string($list);
        }
        return response()->json(['status' => $status, 'errors' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
    }

    public function my_booking_details(REQUEST $request)
    {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];
        $quotes = [];

        $rules = [
            'access_token' => 'required',
            'id' => 'required',

        ];
        $messages = [
            'access_token.required' => "Access token required",
            'id.required' => "ID required",
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $user_id = $this->validateAccesToken($request->access_token);
            
            

            $det = ServiceQuotes::with('room')->select('service_quotes.*', 'services.name as service','doctors.name as doctor_name','groomers.name as groomer_name','feeding_schedules.name as feeding_schedule_text','appointment_types.name as appointment_type_text','grooming_types.name as grooming_service_name')->join('services', 'services.id', 'service_quotes.service_id')->leftjoin('grooming_types', 'grooming_types.id', 'service_quotes.grooming_service')->leftjoin('appointment_types', 'appointment_types.id', 'service_quotes.appointment_type')->leftjoin('feeding_schedules', 'feeding_schedules.id', 'service_quotes.feeding_schedule')->leftjoin('doctors', 'doctors.id', 'service_quotes.doctor_id')->leftjoin('groomers', 'groomers.id', 'service_quotes.groomer_id')->where('user_id', $user_id)->orderBy('service_quotes.id', 'desc')->where('service_quotes.id', $request->id)->first();

            if ($det) {
                $det->status_text = service_status($det->status);
                $det->additional_notes = $det->notes;
                $det->booking_number = '#' . config('global.quote_prefix') . date(date('Ymd', strtotime($det->created_at))) . $det->id;

                if ($request->timezone) {
                    $det->booking_date = api_date_in_timezone($det->created_at, 'd F Y - h:i A', $request->timezone);
                } else {
                    $det->booking_date = date('d F Y - h:i A', strtotime($det->created_at));
                }
                $det->price = $det->quote_price;
                $det->vat = 0;
                $det->total = $det->quote_price;

                $pay_type ="";
                if($det->payment_mode==2){
                    $pay_type ="Card";
                }
                if($det->payment_mode==3){
                    $pay_type ="Apple Pay";
                }
                if($det->payment_mode==4){
                    $pay_type ="Google Pay";
                }
                $det->payment_mode = $pay_type;

                $seleted_foods = ServiceFoods:: select('name')->join('foods','foods.id','service_foods.food_id')->where('service_id',$request->id)->get()->toArray();
                $det->seleted_foods = implode(", ",array_column($seleted_foods,'name'));

                $pets = ServicePets:: select('name')->join('my_pets','my_pets.id','service_pets.pet_id')->where('service_id',$request->id)->get()->toArray();
                $det->pets = implode(", ",array_column($pets,'name'));
                $det->room = $det->room->name??'';

                unset($det->created_at, $det->updated_at, $det->deleted, $det->notes, $det->quote_price);
            }
            $o_data = convert_all_elements_to_string($det);
        }
        return response()->json(['status' => $status, 'errors' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
    }

    public function accept_quote(REQUEST $request)
    {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];

        $rules = [
            'access_token' => 'required',
            'id' => 'required',

        ];
        $messages = [
            'access_token.required' => "Access token required",
            'id.required' => "ID required",
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $user_id = $this->validateAccesToken($request->access_token);
            $det = ServiceQuotes::where('user_id', $user_id)->where('service_quotes.id', $request->id)->first();
            if($det){
                $det->status = 3;
                $det->save();
                $message = "Successfully Accepted";
            }else{
                $status = "0";
                $message = "No details found";
            }
            
        }
        return response()->json(['status' => $status, 'errors' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
    }
    public function cancel_quote(REQUEST $request)
    {
        $status = "1";
        $message = "";
        $o_data = [];
        $errors = [];

        $rules = [
            'access_token' => 'required',
            'id' => 'required',

        ];
        $messages = [
            'access_token.required' => "Access token required",
            'id.required' => "ID required",
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $user_id = $this->validateAccesToken($request->access_token);
            $det = ServiceQuotes::where('user_id', $user_id)->where('service_quotes.id', $request->id)->first();
            if($det){
                $det->status = 4;
                $det->save();
                $message = "Successfully Cancelled";
            }else{
                $status = "0";
                $message = "No details found";
            }
            
        }
        return response()->json(['status' => $status, 'errors' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
    }
}
