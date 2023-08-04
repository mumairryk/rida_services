<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlaytimeStaffs;
use App\Models\VendorModel;
use Illuminate\Http\Request;
use Validator;

class PlaytimeStaff extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!check_permission('playtime_staffs', 'View')) {
            abort(404);
        }
        $page_heading = "Playtime Staffs";
        $vendor = $_GET['vendor'] ?? '';
        $datamain = PlaytimeStaffs::select('playtime_staffs.*', 'users.name as user_name')
            ->where(['playtime_staffs.deleted' => 0]);
        if ($vendor) {
            $datamain = $datamain->where('playtime_staffs.vendor', $vendor);
        }
        $datamain = $datamain->leftjoin('users', 'users.id', '=', 'playtime_staffs.vendor')->orderBy('playtime_staffs.created_at', 'desc')->get();

        return view('admin.playtime_staffs.list', compact('page_heading', 'datamain'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!check_permission('playtime_staffs', 'Create')) {
            abort(404);
        }
        $page_heading = "Playtime Staffs";
        $mode = "create";
        $id = "";
        $users = VendorModel::select('name', 'id', 'users.id as id')->where(['role' => '3', 'deleted' => '0'])
            ->orderBy('name', 'asc')->get();
        return view("admin.playtime_staffs.create", compact('page_heading', 'mode', 'id', 'users'));
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
                'updated_at' => gmdate('Y-m-d H:i:s'),
                'active' => $request->active,
            ];
            if ($file = $request->file("image")) {
                $file_name = time() . uniqid() . "_img." . $file->getClientOriginalExtension();
                $file->storeAs(config('global.doc_image_upload_dir'), $file_name, config('global.upload_bucket'));
                $ins['image'] = $file_name;
            }

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

            if ($request->id != "") {
                $playtime_staffs = PlaytimeStaffs::find($request->id);
                $playtime_staffs->update($ins);
                $status = "1";
                $message = "Playtime Staffs updated succesfully";
            } else {
                $ins['created_at'] = gmdate('Y-m-d H:i:s');
                PlaytimeStaffs::create($ins);
                $status = "1";
                $message = "Playtime Staffs added successfully";
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
    public function edit($id)
    {
        if (!check_permission('playtime_staffs', 'Edit')) {
            abort(404);
        }
        $datamain = PlaytimeStaffs::find($id);
        if ($datamain) {
            $page_heading = "Playtime Staffs ";
            $mode = "edit";
            $id = $datamain->id;
            $image = asset($datamain->image);
            $users = VendorModel::select('name', 'id', 'users.id as id')->where(['role' => '3', 'deleted' => '0'])
                ->orderBy('name', 'asc')->get();
            return view("admin.playtime_staffs.create", compact('page_heading', 'datamain', 'mode', 'id', 'users','image'));
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
        $category = PlaytimeStaffs::find($id);
        if ($category) {
            $category->deleted = 1;
            $category->active = 0;
            $category->updated_at = gmdate('Y-m-d H:i:s');
            $category->save();
            $status = "1";
            $message = "Groomer removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);

    }
    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
        if (PlaytimeStaffs::where('id', $request->id)->update(['active' => $request->status])) {
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
    
}
