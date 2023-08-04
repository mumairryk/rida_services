<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountType;
use App\Models\ActivityType;
use Illuminate\Http\Request;
use Validator;

class ActivityTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!check_permission('activity_tpe', 'View')) {
            abort(404);
        }
        $page_heading = "Activity Type";
        $activityTypes = ActivityType::with('account')->where(['deleted' => '0'])
            ->orderBy('id', 'desc')->get();
        return view('admin.activity_tpe.list', compact('page_heading', 'activityTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!check_permission('activity_tpe', 'Create')) {
            abort(404);
        }
        $page_heading = "Activity Type";
        $mode = "create";
        $id = "";
        $activityType = "";
        $name = "";
        $description = "";
        $account_id = "";
        $accounts = AccountType::where(['deleted' => '0'])->get();
        return view("admin.activity_tpe.create", compact('page_heading', 'account_id', 'accounts', 'activityType', 'description', 'id', 'name'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $status = "0";
        $message = "";
        $errors = [];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'account_id' => 'required',
        ]);
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $input = $request->all();

            $luser_name = strtolower($request->name);
            $check_user_name_exist = ActivityType::whereRaw("LOWER(name) = '$luser_name'")
                ->where('account_id', '=', $request->account_id)
                ->where('id', '!=', $request->id)->get()->toArray();
            if ($check_user_name_exist) {
                $status = "0";
                $message = "name should be unique";
                $errors['name'] = "Already exist";
                echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
                die();
            }

            $ins = [
                'name' => $request->name,
                'account_id' => $request->account_id,
                'description' => $request->description ?? '',
            ];

            if ($request->id != "") {
                $ins['updated_at'] = gmdate('Y-m-d H:i:s');
                $user = ActivityType::find($request->id);
                $user->update($ins);

                $status = "1";
                $message = "Activity Type updated succesfully";
            } else {
                $ins['created_at'] = gmdate('Y-m-d H:i:s');
                $activity_tpe_id = ActivityType::create($ins)->id;

                $status = "1";
                $message = "Activity Type added successfully";
            }
        }
        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!check_permission('activity_tpe', 'Edit')) {
            abort(404);
        }
        $page_heading = "Edit Activity Type";
        $activityType = ActivityType::find($id);
        if (!$activityType) {
            abort(404);
        }

        if ($activityType) {
            $name = $activityType->name;
            $description = $activityType->description;
            $account_id = $activityType->account_id;
            $accounts = AccountType::where(['deleted' => '0'])->get();
            return view("admin.activity_tpe.create", compact('page_heading', 'account_id', 'accounts',
                'name', 'description', 'id'));
        } else {
            abort(404);
        }
    }


    public function destroy($id)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $datatb = ActivityType::find($id);
        if ($datatb) {
            $datatb->deleted = 1;
            $datatb->save();
            $status = "1";
            $message = "Activity Type removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
    }

    public function get_activities(Request $request){
        
        $activity_types = ActivityType::select('id','name as activity_name')->where(['deleted' => 0,'account_id'=>$request->account_id])->get();
        $html = view("admin.activity_tpe.options", compact('activity_types'))->render();
        return response()->json(['html' => $html],200);    
    }
}