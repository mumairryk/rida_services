<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomTypes;
use App\Models\VendorModel;
use Illuminate\Http\Request;
use Validator;

class RoomType extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!check_permission('room_types', 'View')) {
            abort(404);
        }
        $page_heading = "Cage Types";
        $vendor = $_GET['vendor'] ?? '';
        $datamain = RoomTypes::where(['room_types.deleted' => 0])->orderBy('room_types.created_at', 'desc')->get();

        return view('admin.room_types.list', compact('page_heading', 'datamain'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!check_permission('room_types', 'Create')) {
            abort(404);
        }
        $page_heading = "Cage Types";
        $mode = "create";
        $id = "";
        return view("admin.room_types.create", compact('page_heading', 'mode', 'id'));
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
            
        ];
        

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                'name.required' => 'Name is required',
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
                'name' => $request->name,
                'updated_at' => gmdate('Y-m-d H:i:s'),
                'active' => $request->active,
            ];

            if ($request->id != "") {
                $room_types = RoomTypes::find($request->id);
                $room_types->update($ins);
                $status = "1";
                $message = "Cage Type updated succesfully";
            } else {
                $ins['created_at'] = gmdate('Y-m-d H:i:s');
                RoomTypes::create($ins);
                $status = "1";
                $message = "Cage Type added successfully";
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
        if (!check_permission('room_types', 'Edit')) {
            abort(404);
        }
        $datamain = RoomTypes::find($id);
        if ($datamain) {
            $page_heading = "Cage Types ";
            $mode = "edit";
            $id = $datamain->id;

            return view("admin.room_types.create", compact('page_heading', 'datamain', 'mode', 'id'));
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
        $category = RoomTypes::find($id);
        if ($category) {
            $category->deleted = 1;
            $category->active = 0;
            $category->updated_at = gmdate('Y-m-d H:i:s');
            $category->save();
            $status = "1";
            $message = "Cage Type removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);

    }
    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
        if (RoomTypes::where('id', $request->id)->update(['active' => $request->status])) {
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
