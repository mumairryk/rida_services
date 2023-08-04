<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppointmentTimes;
use App\Models\Breeds;
use App\Models\RoomTypes;
use App\Models\Species;
use Illuminate\Http\Request;
use Validator;

class Breed extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!check_permission('breed', 'View')) {
            abort(404);
        }
        $page_heading = "Breed";
        $datamain = Breeds::select('breeds.*', 'species.name as species_name')->leftjoin('species', 'species.id', 'breeds.species')->orderBy('breeds.created_at', 'desc')->where(['breeds.deleted' => 0])
            ->get();

        return view('admin.breed.list', compact('page_heading', 'datamain'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!check_permission('breed', 'Create')) {
            abort(404);
        }
        $page_heading = "Breed";
        $mode = "create";
        $id = "";
        $name = "";
        $active = "1";
        $species = '';
        $appoint_time_id = '';
        $room_type_id = '';
        $species_list = Species::where(['deleted' => 0])->orderBy('name', 'asc')->get();
        $appoint_times = AppointmentTimes::where(['deleted' => 0])->orderBy('name', 'asc')->get();
        $room_types = RoomTypes::where(['deleted' => 0])->orderBy('name', 'asc')->get();
        return view("admin.breed.create", compact('page_heading', 'mode', 'id', 'name', 'active', 'species', 'species_list', 'room_type_id', 'appoint_time_id', 'room_types', 'appoint_times'));
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
            $check_exist = Breeds::where(['deleted' => 0, 'name' => $request->name])->where('id', '!=', $request->id)->get()->toArray();
            if (empty($check_exist)) {
                $ins = [
                    'name' => $request->name,
                    'species' => $request->species,
                    'updated_at' => gmdate('Y-m-d H:i:s'),
                    'active' => $request->active,
                    'appoint_time_id' => $request->appoint_time_id,
                    'room_type_id' => $request->room_type_id,
                ];

                if ($request->id != "") {
                    $breed = Breeds::find($request->id);
                    $breed->update($ins);
                    $status = "1";
                    $message = "Breed updated succesfully";
                } else {
                    $ins['created_at'] = gmdate('Y-m-d H:i:s');
                    Breeds::create($ins);
                    $status = "1";
                    $message = "Breed added successfully";
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
        if (!check_permission('breed', 'Edit')) {
            abort(404);
        }
        $datamain = Breeds::find($id);
        if ($datamain) {
            $page_heading = "Breeds ";
            $mode = "edit";
            $id = $datamain->id;
            $name = $datamain->name;
            $active = $datamain->active;
            $species = $datamain->species;
            $species_list = Species::where(['deleted' => 0])->orderBy('name', 'asc')->get();

            $appoint_time_id = $datamain->appoint_time_id;
            $room_type_id = $datamain->room_type_id;
            $species_list = Species::where(['deleted' => 0])->orderBy('name', 'asc')->get();
            $appoint_times = AppointmentTimes::where(['deleted' => 0])->orderBy('name', 'asc')->get();
            $room_types = RoomTypes::where(['deleted' => 0])->orderBy('name', 'asc')->get();
            return view("admin.breed.create", compact('page_heading', 'datamain', 'mode', 'id', 'name', 'active', 'species', 'species_list', 'room_type_id', 'appoint_time_id', 'room_types', 'appoint_times'));
        } else {
            abort(404);
        }
    }
    public function get_by_species(Request $request)
    {
        $breeds = Breeds::select('id', 'name')->where(['deleted' => 0, 'species' => $request->id])->orderBy('name', 'asc')->get();
        echo json_encode(['breeds' => $breeds]);
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
        $category = Breeds::find($id);
        if ($category) {
            $category->deleted = 1;
            $category->active = 0;
            $category->updated_at = gmdate('Y-m-d H:i:s');
            $category->save();
            $status = "1";
            $message = "Breed removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);

    }
    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
        if (Breeds::where('id', $request->id)->update(['active' => $request->status])) {
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
