<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CurrentProjectStatus;
use Illuminate\Http\Request;
use Validator;

class CurrentProjectStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!check_permission('current_project_status','View')) {
            abort(404);
        }
        $page_heading = "Current project status";
        $datamain = CurrentProjectStatus::get();
        return view('admin.current_project_status.list', compact('page_heading', 'datamain'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!check_permission('current_project_status','Create')) {
            abort(404);
        }
        $page_heading = "Current project status";
        $mode = "create";
        $id = "";
        $prefix = "";
        $name = "";
        $dial_code = "";
        $image = "";
        $active = "1";
        return view("admin.current_project_status.create", compact('page_heading', 'mode', 'id', 'name', 'dial_code', 'active','prefix'));
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

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $input = $request->all();
            $check_exist = CurrentProjectStatus::where(['name' => $request->name])->where('id', '!=', $request->id)->get()->toArray();
            if (empty($check_exist)) {
                $ins = [
                    'name' => $request->name,
                    'status' => $request->active,
                ];

                if ($request->id != "") {
                    $ins['updated_at'] = gmdate('Y-m-d H:i:s');
                    $country = CurrentProjectStatus::find($request->id);
                    $country->update($ins);
                    $status = "1";
                    $message = "Current project status updated succesfully";
                } else {
                    $ins['created_at'] = gmdate('Y-m-d H:i:s');
                    CurrentProjectStatus::create($ins);
                    $status = "1";
                    $message = "Current project status added successfully";
                }
            } else {
                $status = "0";
                $message = "Name should be unique";
                $errors['name'] = $request->name . " already added";
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
        if (!check_permission('current_project_status','Edit')) {
            abort(404);
        }
        $datamain = CurrentProjectStatus::find($id);
        if ($datamain) {
            $page_heading = "Current project status";
            $mode = "edit";
            $id = $datamain->id;
            $name = $datamain->name;
            $active = $datamain->status;
            return view("admin.current_project_status.create", compact('page_heading', 'mode', 'id', 'name', 'active'));
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
        $datamain = CurrentProjectStatus::find($id);
        if ($datamain) {
           CurrentProjectStatus::where('id',$id)->delete();
            $status = "1";
            $message = "Current project status removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
    }
}
