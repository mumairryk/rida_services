<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublicBusinessInfos;
use Illuminate\Http\Request;
use Validator;

class PublicBusinessInfo extends Controller
{
    public function index()
    {
        if (!check_permission('public_business_info','View')) {
            abort(404);
        }
        $page_heading = "Public Business Infos";
        $infos = PublicBusinessInfos::where(['deleted' => 0])->orderby('created_at','desc')->get();
        return view('admin.public_business_infos.list', compact('page_heading', 'infos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!check_permission('public_business_info','Create')) {
            abort(404);
        }
        $page_heading = "Public Business Infos";
        $mode = "create";
        $id = "";
        $title = "";
        return view("admin.public_business_infos.create", compact('page_heading', 'mode', 'id', 'title'));
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
            'title' => 'required',
        ]);
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $input = $request->all();
            $check_exist = PublicBusinessInfos::where(['deleted' => 0, 'title' => $request->title])->where('id', '!=', $request->id)->get()->toArray();
            if (empty($check_exist)) {
                $ins = [
                    'title' => $request->title,
                ];
                if ($request->id != "") {
                    $ins['updated_at'] = gmdate('Y-m-d H:i:s');
                    $info = PublicBusinessInfos::find($request->id);
                    $info->update($ins);
                    $status = "1";
                    $message = "Info updated succesfully";
                } else {
                    $ins['created_at'] = gmdate('Y-m-d H:i:s');
                    PublicBusinessInfos::create($ins);
                    $status = "1";
                    $message = "Info added successfully";
                }
            } else {
                $status = "0";
                $message = "Title should be unique";
                $errors['title'] = $request->title . " already added";
            }

        }
        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
    }

    public function edit($id)
    {
        if (!check_permission('public_business_info','Edit')) {
            abort(404);
        }
        $info = PublicBusinessInfos::find($id);
        if ($info) {
            $page_heading = "Public Business Infos";
            $mode = "edit";
            $id = $info->id;
            $title = $info->title;
            return view("admin.public_business_infos.create", compact('page_heading', 'mode', 'id', 'title'));
        } else {
            abort(404);
        }
    }

    public function destroy($id)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $info = PublicBusinessInfos::find($id);
        if ($info) {
            $info->deleted = 1;
            $info->active = 0;
            $info->save();
            $status = "1";
            $message = "Info removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
    }
    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
        if (PublicBusinessInfos::where('id', $request->id)->update(['active' => $request->status])) {
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
