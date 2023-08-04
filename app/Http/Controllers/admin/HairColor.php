<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HairColors;
use Illuminate\Http\Request;
use Validator;

class HairColor extends Controller
{
    public function index()
    {
        if (!check_permission('hair_colors','View')) {
            abort(404);
        }
        $page_heading = "Hair Colors";
        $colors = HairColors::where(['deleted' => 0])->get();
        return view('admin.hair_color.list', compact('page_heading', 'colors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!check_permission('hair_colors','Create')) {
            abort(404);
        }
        $page_heading = "Hair Colors";
        $mode = "create";
        $id = "";
        $color = "";
        $name = "";
        return view("admin.hair_color.create", compact('page_heading', 'mode', 'id', 'name', 'color'));
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
            $check_exist = HairColors::where(['deleted' => 0, 'color' => $request->color])->where('id', '!=', $request->id)->get()->toArray();
            if (empty($check_exist)) {
                $ins = [
                    'name' => $request->name,
                    'color' => $request->color,
                ];

                if ($request->id != "") {
                    $ins['updated_at'] = gmdate('Y-m-d H:i:s');
                    $color = HairColors::find($request->id);
                    $color->update($ins);
                    $status = "1";
                    $message = "Color updated succesfully";
                } else {
                    $ins['created_at'] = gmdate('Y-m-d H:i:s');
                    HairColors::create($ins);
                    $status = "1";
                    $message = "Color added successfully";
                }
            } else {
                $status = "0";
                $message = "Color should be unique";
                $errors['color'] = $request->color . " already added";
            }

        }
        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
    }

    public function edit($id)
    {
        if (!check_permission('hair_colors','Edit')) {
            abort(404);
        }
        $color = HairColors::find($id);
        if ($color) {
            $page_heading = "Hair Colors";
            $mode = "edit";
            $id = $color->id;
            $name = $color->name;
            $color = $color->color;
            return view("admin.hair_color.create", compact('page_heading', 'mode', 'id', 'name', 'color'));
        } else {
            abort(404);
        }
    }

    public function destroy($id)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $color = HairColors::find($id);
        if ($color) {
            $color->deleted = 1;
            $color->active = 0;
            $color->save();
            $status = "1";
            $message = "Color removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
    }
    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
        if (HairColors::where('id', $request->id)->update(['active' => $request->status])) {
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
