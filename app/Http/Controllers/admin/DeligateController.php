<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeligateModel;
use Illuminate\Http\Request;
use App\Models\IndustryTypes;
use Validator;

class DeligateController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (!check_permission('deligates','View')) {
            abort(404);
        }
        $page_heading = "Deligates";
        $datamain = DeligateModel::select('deligates.*')
        ->get();
        
        return view('admin.deligates.list', compact('page_heading', 'datamain'));
    }

    public function create()
    {
        if (!check_permission('deligates','Create')) {
            abort(404);
        }
        $page_heading = "Deligates";
        $mode = "create";
        $id = "";
        $deligate_name = "";
        $deligate_icon = "";
        $deligate_status = "1";
        $shipping_charge= '';

        return view("admin.deligates.create", compact('page_heading', 'mode', 'id', 'deligate_name', 'deligate_icon', 'deligate_status','shipping_charge'));
    }


    public function store(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $redirectUrl = '';

        $validator = Validator::make($request->all(), [
            'deligate_name' => 'required',
        ]);
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $input = $request->all();

            $check_exist = DeligateModel::where(['deligate_name' => $request->deligate_name])->where('id', '!=', $request->id)->get()->toArray();
            if (empty($check_exist)) {
                $ins = [
                    'deligate_name' => $request->deligate_name,
                    'updated_at' => gmdate('Y-m-d H:i:s'),
                    'deligate_status' => $request->deligate_status,
                    'shipping_charge'=> $request->shipping_charge??0
                ];

                if($request->file("deligate_icon")){
                    $response = image_upload($request,'deligates','deligate_icon');
                    if($response['status']){
                        $ins['deligate_icon'] = $response['link'];
                    }
                }

                if ($request->id != "") {
                    $brand = DeligateModel::find($request->id);
                    $brand->update($ins);
                    $status = "1";
                    $message = "Deligate updated succesfully";
                } else {
                    $ins['created_at'] = gmdate('Y-m-d H:i:s');
                    DeligateModel::create($ins);
                    $status = "1";
                    $message = "Deligate added successfully";
                }
            } else {
                $status = "0";
                $message = "Deligate Name should be unique";
                $errors['name'] = $request->deligate_name . " already added";
            }

        }
        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
    }

    public function edit($id)
    {
        if (!check_permission('deligates','edit')) {
            abort(404);
        }
        $datamain = DeligateModel::find($id);

        if ($datamain) {
            $page_heading = "Deligates";
            $mode = "edit";
            $id = $datamain->id;
            $deligate_name = $datamain->deligate_name;
            $deligate_icon = $datamain->deligate_icon;
            $deligate_status = $datamain->deligate_status;
            $shipping_charge = $datamain->shipping_charge;

        return view("admin.deligates.create", compact('page_heading', 'mode', 'id', 'deligate_name', 'deligate_icon', 'deligate_status','shipping_charge'));
        } else {
            abort(404);
        }
    }


    public function destroy($id)
    {

        $status = "0";
        $message = "";
        $o_data = [];
        $deligate = DeligateModel::find($id);
        if ($deligate) {
            $deligate->delete();
             $status = "1";
            $message = "Deligate removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);

    }
    public function change_status(Request $request)
    {   
        $status = "0";
        $message = "";

        if (DeligateModel::where('id', $request->id)->update(['deligate_status' => $request->status])) {
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
