<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Breeds;
use App\Models\MyPets;
use App\Models\Species;
use App\Models\VendorModel;
use Illuminate\Http\Request;
use Validator;

class Pets extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!check_permission('my_pets', 'View')) {
            abort(404);
        }
        $page_heading = "Pets";
        $user_id = $_GET['customer'] ?? '';
        $datamain = MyPets::select('my_pets.*', 'users.name as user_name', 'breeds.name as breed_name')
            ->where(['my_pets.deleted' => 0]);
        if ($user_id) {
            $datamain = $datamain->where('user_id', $user_id);
        }
        $datamain = $datamain->leftjoin('users', 'users.id', '=', 'my_pets.user_id')->leftjoin('breeds', 'breeds.id', '=', 'my_pets.breed_id')->orderBy('my_pets.created_at', 'desc')->get();

        return view('admin.my_pets.list', compact('page_heading', 'datamain'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!check_permission('my_pets', 'Create')) {
            abort(404);
        }
        $page_heading = "Pets";
        $mode = "create";
        $id = "";
        $breeds = ''; //Breeds::where(['deleted' => 0])->orderBy('name', 'asc')->get();
        $users = VendorModel::select('name', 'id', 'users.id as id')->where(['role' => '2', 'deleted' => '0'])
            ->orderBy('name', 'asc')->get();
        $species_list = Species::where(['deleted' => 0])->orderBy('name', 'asc')->get();
        return view("admin.my_pets.create", compact('page_heading', 'mode', 'id', 'breeds', 'users', 'species_list'));
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

            $ins = [
                'user_id' => $request->user_id,
                'name' => $request->name,
                'species' => $request->species,
                'breed_id' => $request->breed_id,
                'sex' => $request->sex,
                'dob' => date('Y-m-d', strtotime($request->dob)),
                'weight' => $request->weight,
                'food' => $request->food,
                'additional_notes' => $request->additional_notes ?? '',
                'updated_at' => gmdate('Y-m-d H:i:s'),
                'active' => $request->active,
            ];
            if ($file = $request->file("image")) {
                $file_name = time() . uniqid() . "_img." . $file->getClientOriginalExtension();
                $file->storeAs(config('global.pet_image_upload_dir'), $file_name, config('global.upload_bucket'));
                $ins['image'] = $file_name;
            }

            if ($request->id != "") {
                $my_pets = MyPets::find($request->id);
                $my_pets->update($ins);
                $status = "1";
                $message = "Pets updated succesfully";
            } else {
                $ins['created_at'] = gmdate('Y-m-d H:i:s');
                MyPets::create($ins);
                $status = "1";
                $message = "Pets added successfully";
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
        if (!check_permission('my_pets', 'Edit')) {
            abort(404);
        }
        $datamain = MyPets::find($id);
        if ($datamain) {
            $page_heading = "Pets ";
            $mode = "edit";
            $id = $datamain->id;
            $breeds = Breeds::where(['deleted' => 0, 'species' => $datamain->species])->orderBy('name', 'asc')->get();
            $users = VendorModel::select('name', 'id', 'users.id as id')->where(['role' => '2', 'deleted' => '0'])
                ->orderBy('name', 'asc')->get();
            $species_list = Species::where(['deleted' => 0])->orderBy('name', 'asc')->get();
            $image = $datamain->image;
            return view("admin.my_pets.create", compact('page_heading', 'datamain', 'mode', 'id', 'breeds', 'users', 'species_list','image'));
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
        $category = MyPets::find($id);
        if ($category) {
            $category->deleted = 1;
            $category->active = 0;
            $category->updated_at = gmdate('Y-m-d H:i:s');
            $category->save();
            $status = "1";
            $message = "Pet removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);

    }
    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
        if (MyPets::where('id', $request->id)->update(['active' => $request->status])) {
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
    public function sort(Request $request)
    {
        if ($request->ajax()) {
            $status = 0;
            $message = '';

            $items = $request->items;
            $items = explode(",", $items);
            $sorted = Categories::sort_item($items);
            if ($sorted) {
                $status = 1;
            }

            echo json_encode(['status' => $status, 'message' => $message]);

        } else {
            $page_heading = "Sort Categories";

            $list = Categories::where(['deleted' => 0, 'parent_id' => 0])->get();

            return view("admin.sort", compact('page_heading', 'list'));
        }
    }
}
