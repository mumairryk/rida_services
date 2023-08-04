<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctors;
use App\Models\VendorModel;
use App\Models\DoctorCalenderTemp;
use App\Models\DoctorCalender;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;

class Doctor extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!check_permission('doctors', 'View')) {
            abort(404);
        }
        $page_heading = "Doctors";
        $vendor = $_GET['vendor'] ?? '';
        $datamain = Doctors::select('doctors.*', 'users.name as user_name')
            ->where(['doctors.deleted' => 0]);
        if ($vendor) {
            $datamain = $datamain->where('doctors.vendor', $vendor);
        }
        $datamain = $datamain->leftjoin('users', 'users.id', '=', 'doctors.vendor')->orderBy('doctors.created_at', 'desc')->get();

        return view('admin.doctors.list', compact('page_heading', 'datamain'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!check_permission('doctors', 'Create')) {
            abort(404);
        }
        $page_heading = "Doctors";
        $mode = "create";
        $id = "";
        $un_id = uniqid().time();
        $users = VendorModel::select('name', 'id', 'users.id as id')->where(['role' => '3', 'deleted' => '0'])
            ->orderBy('name', 'asc')->get();
        return view("admin.doctors.create", compact('page_heading', 'mode', 'id', 'users','un_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $redirectUrl = '';


        $rules = [
            'name' => 'required',
            'vendor' => 'required',
            
        ];
        if($request->sunday)
        {
            $rules['sun_from'] = 'required';
            $rules['sun_to'] = 'required';
        }
        if($request->monday)
        {
            $rules['mon_from'] = 'required';
            $rules['mon_to'] = 'required';
        }
        if($request->tuesday)
        {
            $rules['tues_from'] = 'required';
            $rules['tues_to'] = 'required';
        }
        if($request->wednesday)
        {
            $rules['wed_from'] = 'required';
            $rules['wed_to'] = 'required';
        }
        if($request->thursday)
        {
            $rules['thurs_from'] = 'required';
            $rules['thurs_to'] = 'required';
        }
        if($request->friday)
        {
            $rules['fri_from'] = 'required';
            $rules['fri_to'] = 'required';
        }
        if($request->saturday)
        {
            $rules['sat_from'] = 'required';
            $rules['sat_to'] = 'required';
        }
        

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                'name.required' => 'Name is required',
                'vendor.required' => 'Vendor is required',
                'sun_from.required' => 'Required start time',
                'sun_to.required' => 'Required end time',
                'mon_from.required' => 'Required start time',
                'mon_to.required' => 'Required end time',
                'tues_from.required' => 'Required start time',
                'tues_to.required' => 'Required end time',
                'wed_from.required' => 'Required start time',
                'wed_to.required' => 'Required end time',
                'thurs_from.required' => 'Required start time',
                'thurs_to.required' => 'Required end time',
                'fri_from.required' => 'Required start time',
                'fri_to.required' => 'Required end time',
                'sat_from.required' => 'Required start time',
                'sat_to.required' => 'Required end time',
            ]
        );
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            foreach ($validator->messages()->toArray() as $key => $row) {
                $errors[0][$key] = $row[0];
            }
        }  else {
            $input = $request->all();

            
            $ins = [
                'vendor' => $request->vendor,
                'name' => $request->name,
                'type' => $request->type,
                'qualification' => $request->qualification,
                'updated_at' => gmdate('Y-m-d H:i:s'),
                'active' => $request->active,
            ];

            $days = Config('global.days') ;
            foreach($days as $key =>$val){
                if(isset($request->{$val})) {
                    $ins[$val] = $request->{$val} ;
                    $ins[$key.'_from'] = $request->{$key.'_from'} ;
                    $ins[$key.'_to'] = $request->{$key.'_to'} ;
                } else {
                    $ins[$val] = 0;
                    $ins[$key.'_from'] = '00:00';
                    $ins[$key.'_to'] = '00:00';
                }
            }

            if ($file = $request->file("image")) {
                $file_name = time() . uniqid() . "_img." . $file->getClientOriginalExtension();
                $file->storeAs(config('global.doc_image_upload_dir'), $file_name, config('global.upload_bucket'));
                $ins['image'] = $file_name;
            }

            if ($file = $request->file("document")) {
                $file_name = time() . uniqid() . "_doc." . $file->getClientOriginalExtension();
                $file->storeAs(config('global.doc_image_upload_dir'), $file_name, config('global.upload_bucket'));
                $ins['document'] = $file_name;
            }

            if ($request->id != "") {
                $doctors = Doctors::find($request->id);
                $doctors->update($ins);

                //get all temps 
                $list  =DoctorCalenderTemp::where(['device_id'=>$request->device_id])->get();
                if($list->count() > 0){
                    foreach($list as $key){
                        $cl=new DoctorCalender();
                        $cl->doctor_id = $request->id;
                        $cl->event_date = $key->event_date;
                        $cl->start_time = $key->start_time;
                        $cl->end_time = $key->end_time;
                        $cl->event_title = $key->event_title;
                        $cl->created_at = gmdate('Y-m-d H:i:s');
                        $cl->updated_at = gmdate('Y-m-d H:i:s');
                        $cl->save();
                    }
                    DoctorCalenderTemp::where(['device_id'=>$request->device_id])->delete();
                }
                $status = "1";
                $message = "Doctors updated succesfully";
            } else {
                $ins['created_at'] = gmdate('Y-m-d H:i:s');
                $doctor = Doctors::create($ins);

                //get all temps 
                $list  =DoctorCalenderTemp::where(['device_id'=>$request->device_id])->get();
                if($list->count() > 0){
                    foreach($list as $key){
                        $cl=new DoctorCalender();
                        $cl->doctor_id = $doctor->id;
                        $cl->event_date = $key->event_date;
                        $cl->start_time = $key->start_time;
                        $cl->end_time = $key->end_time;
                        $cl->event_title = $key->event_title;
                        $cl->created_at = gmdate('Y-m-d H:i:s');
                        $cl->updated_at = gmdate('Y-m-d H:i:s');
                        $cl->save();
                    }
                    DoctorCalenderTemp::where(['device_id'=>$request->device_id])->delete();
                }

                $status = "1";
                $message = "Doctors added successfully";
            }

        }
        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

  
function getTimeSlots($startTime, $endTime, $interval)
{
    $start = Carbon::parse($startTime);
    $end = Carbon::parse($endTime);
    $slots = [];

    while ($start->lt($end)) {
        $slots[] = $start->format('h:i A');
        $start->addMinutes($interval);
    }

    return $slots;
}

    public function edit($id)
    {

//         $startTime = '01:00:00';
//         $endTime = '17:00:00';
//         $interval = 60; // in minutes

//         $timeSlots = $this->getTimeSlots($startTime, $endTime, $interval);

//         // Output the time slots
//         foreach ($timeSlots as $slot) {
//             echo $slot . "\n";
//         }
// dd();
        if (!check_permission('doctors', 'Edit')) {
            abort(404);
        }
        $datamain = Doctors::find($id);
        if ($datamain) {
            $page_heading = "Doctors ";
            $mode = "edit";
            $id = $datamain->id;
            $image = asset($datamain->image);
            $document = asset($datamain->document);
            $users = VendorModel::select('name', 'id', 'users.id as id')->where(['role' => '3', 'deleted' => '0'])
                ->orderBy('name', 'asc')->get();
                $un_id = uniqid().time();
            return view("admin.doctors.create", compact('page_heading', 'datamain', 'mode', 'id', 'users','image','document','un_id'));
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $category = Doctors::find($id);
        if ($category) {
            $category->deleted = 1;
            $category->active = 0;
            $category->updated_at = gmdate('Y-m-d H:i:s');
            $category->save();
            $status = "1";
            $message = "Doctor removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);

    }
    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
        if (Doctors::where('id', $request->id)->update(['active' => $request->status])) {
            $status = "1";
            $msg = "Successfully activated";
            if (!$request->status) {
                $msg = "Successfully deactivated";
            }
            $message = $msg;
        } else {
            $message = "Something went wrong";
        }
        echo json_encode(['status' => $status, 'message' => $message]);
    }
    public function get_events(REQUEST $request){
        $start= $request->start;
        $end= $request->end;
        $un_id = $request->un_id;
        $doctor_id = $request->doctor_id;
        $start_date = gmdate('Y-m-d',strtotime($start));
        $end_date = gmdate('Y-m-d',strtotime($end));

        $response = [];
        if($doctor_id > 0){
            $list = DoctorCalender::where(['doctor_id'=>$doctor_id])->where('event_date','>=',$start_date)->where('event_date','<=',$end_date)->get();
            foreach($list as $item){
                $response[]= [
                    'title'=>gmdate('h:i:A',strtotime($item->start_time)).' - '.gmdate('h:i:A',strtotime($item->end_time)),
                    'start' => $item->event_date." ".$item->start_time,
                    'end' => $item->event_date." ".$item->end_time,
                    "event_uid"=> $item->id,
                    'type'=>'orginal',
                    'event_title'=>$item->event_title,
                    'event_start_time'=>gmdate('h:i a',strtotime($item->start_time)),
                    'event_end_time'=>gmdate('h:i a',strtotime($item->end_time)),
                    'event_date'=>$item->event_date
                ];
            }
        }
        $list = DoctorCalenderTemp::where(['device_id'=>$un_id])->get();
        //$list = DoctorCalenderTemp::get();
        foreach($list as $item){
            $response[]= [
                // 'title'=>str_replace("m","",gmdate('h:i A',strtotime($item->end_time))),
                'title'=>gmdate('h:i:A',strtotime($item->start_time)).' - '.gmdate('h:i:A',strtotime($item->end_time)),
                'start' => $item->event_date." ".$item->start_time,
                'end' => $item->event_date." ".$item->end_time,
                "event_uid"=> $item->id,
                'type'=>'temp',
                'event_title'=>$item->event_title,
                'event_start_time'=>gmdate('h:i a',strtotime($item->start_time)),
                'event_end_time'=>gmdate('h:i a',strtotime($item->end_time)),
                'event_date'=>$item->event_date
            ];
        }
       // echo gmdate('Y-m-d H:i:s',strtotime($end));
        // $response = [
        //     [
        //         "title"=> 'Long Event',
        //         "start"=> '2023-01-07',
        //         "end"=> '2023-01-10'
        //     ],
        //     [
        //         "title"=> 'Long Event',
        //         "start"=> '2023-01-20 10:00:00',
        //         "end"=> '2023-01-20 11:00:00'
        //     ]
        // ];
        echo json_encode($response);
    }

    public function add_event(REQUEST $request){
        $status = "0";
        $message = "";
        $event = [];
        $checkFlag = false;
        $edit_uid = $request->edit_uid;
        if(isset($request->event_start_time) && $request->event_start_time && isset($request->event_end_time) && $request->event_end_time){

        }else{
            echo json_encode(['status'=>$status,'event'=>$event,'message'=>"Invalid time"]);
            die();
        }
        if(strtotime($request->event_start_time) >= strtotime($request->event_end_time)){
            $message = "Start time should be less than end time";
            echo json_encode(['status'=>$status,'event'=>$event,'message'=>$message]);
            exit;
        }
        if($request->doctor_id > 0){
          $check = DoctorCalender::where('start_time','>=',$request->event_start_time)->where('end_time','>=',$request->event_start_time)->where(['event_date'=>$request->event_date,'doctor_id'=>$request->doctor_id])->where('id','!=',$edit_uid)->get()->count();
          if($check > 0){
            $checkFlag = TRUE;
          }else{
            $check = DoctorCalender::where('end_time','>=',$request->event_start_time)->where('end_time','<=',$request->event_end_time)->where(['event_date'=>$request->event_date,'doctor_id'=>$request->doctor_id])->where('id','!=',$edit_uid)->get()->count();
            if($check > 0){
                $checkFlag = TRUE;
            }
          }
        }
        if($checkFlag == TRUE){
            $message = "Time slot is already taken";
            echo json_encode(['status'=>$status,'event'=>$event,'message'=>$message]);
            exit;
        }

        $check = DoctorCalenderTemp::where(['device_id'=>$request->unique_id,'event_date'=>$request->event_date])->where('start_time','>=',$request->event_start_time)->where('end_time','>=',$request->event_start_time)->where('id','!=',$edit_uid)->get()->count();
        if($check > 0){
            $checkFlag = TRUE;
          }else{
            $check = DoctorCalenderTemp::where(['device_id'=>$request->unique_id,'event_date'=>$request->event_date])->where('end_time','<=',$request->event_end_time)->where('end_time','>=',$request->event_start_time)->where('id','!=',$edit_uid)->get()->count();
            if($check > 0){
                $checkFlag = TRUE;
            }
          }
          if($checkFlag == TRUE){
                $message = "Time slot is already taken";
                echo json_encode(['status'=>$status,'event'=>$event,'message'=>$message]);
                exit;
            }
            if($edit_uid > 0){
                if($request->edit_type == 'orginal'){
                    $item = DoctorCalender::find($edit_uid);
                    $item->event_title = $request->event_title;
                    $item->start_time  = $request->event_start_time;
                    $item->end_time  = $request->event_end_time;
                    $item->updated_at = gmdate('Y-m-d H:i:s');
                    $item->save();
                    $status = "1";
                }else{
                    $item = DoctorCalenderTemp::find($edit_uid);
                    $item->device_id = $request->unique_id;
                    $item->event_title = $request->event_title;
                    $item->start_time  = $request->event_start_time;
                    $item->end_time  = $request->event_end_time;
                    $item->doctor_id = $request->doctor_id??0;
                    $item->updated_at = gmdate('Y-m-d H:i:s');
                    $item->save();
                    $status = "1";
                }
            }else{
                $item = new DoctorCalenderTemp();
                $item->device_id = $request->unique_id;
                $item->event_title = $request->event_title;
                $item->event_date  = $request->event_date;
                $item->start_time  = $request->event_start_time;
                $item->end_time  = $request->event_end_time;
                $item->doctor_id = $request->doctor_id??0;
                $item->created_at = gmdate('Y-m-d H:i:s');
                $item->save();
                if($item->id){
                    $status = "1";
                    $event = [
                        'title'=>$item->event_title."(".$item->start_time."-".$item->end_time.")",
                        'start'=>$item->event_date." ".$item->start_time,
                        'end'=>$item->event_date." ".$item->end_time,
                        'allDay'=>false
                    ];
                }
            }
        echo json_encode(['status'=>$status,'event'=>$event,'message'=>$message]);
    }

    public function remove_event(REQUEST $request){
        $status = "1";
        $message = "";
        if($request->type == 'orginal'){
            DoctorCalender::where(['id'=>$request->id])->delete();
        }else{
            DoctorCalenderTemp::where(['id'=>$request->id])->delete();
        }

        echo json_encode(['status'=>$status]);
    }
}
