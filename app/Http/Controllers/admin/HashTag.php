<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HashTags;
use Illuminate\Http\Request;
use Validator;

class HashTag extends Controller
{
    public function index()
    {
        if (!check_permission('hash_tags','View')) {
            abort(404);
        }
        $page_heading = "Hash Tags";
        $tags = HashTags::orderby('created_at','desc')->get();
        return view('admin.hash_tags.list', compact('page_heading', 'tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!check_permission('hash_tags','Create')) {
            abort(404);
        }
        $page_heading = "Hash Tags";
        $mode = "create";
        $id = "";
        $tag = "";
        return view("admin.hash_tags.create", compact('page_heading', 'mode', 'id', 'tag'));
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
            'tag' => 'required',
        ]);
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $input = $request->all();
            $check_exist = HashTags::where(['tag' => $request->tag])->where('id', '!=', $request->id)->get()->toArray();
            if (empty($check_exist)) {
                $ins = [
                    'tag' => $request->tag,
                ];
                if ($request->id != "") {
                    $ins['updated_at'] = gmdate('Y-m-d H:i:s');
                    $info = HashTags::find($request->id);
                    $info->update($ins);
                    $status = "1";
                    $message = "Tag updated succesfully";
                } else {
                    $ins['created_at'] = gmdate('Y-m-d H:i:s');
                    HashTags::create($ins);
                    $status = "1";
                    $message = "Tag added successfully";
                }
            } else {
                $status = "0";
                $message = "Tag should be unique";
                $errors['tag'] = $request->tag . " already added";
            }

        }
        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
    }

    public function edit($id)
    {
        if (!check_permission('hash_tags','Edit')) {
            abort(404);
        }
        $info = HashTags::find($id);
        if ($info) {
            $page_heading = "Hash Tags";
            $mode = "edit";
            $id = $info->id;
            $tag = $info->tag;
            return view("admin.hash_tags.create", compact('page_heading', 'mode', 'id', 'tag'));
        } else {
            abort(404);
        }
    }

    public function destroy($id)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $info = HashTags::find($id);
        if ($info) {
            $info->delete();
            $status = "1";
            $message = "Tag removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
    }
}
