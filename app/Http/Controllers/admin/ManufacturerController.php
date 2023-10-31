<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manufacturer;

class ManufacturerController extends Controller
{
    public function index()
    {
        $make = Manufacturer::get();
        return view('admin.manufacturer.list',compact('make'));   
    }

    public function create()
    {
        return view('admin.manufacturer.create');
    }

    public function store(Request $request){
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $redirectUrl = '';
        if($request->file("image")){
            $dir = config('global.upload_path') . "/" . config('global.manufacturer_images');
            $file_name = time() . uniqid() . "." . $request->file("image")->getClientOriginalExtension();
            $request->file("image")->storeAs(config('global.manufacturer_images'), $file_name, config('global.upload_bucket'));
        }
        Manufacturer::create([
            'name' => $request->name,
            'status' => $request->active,
            'logo' => $file_name,
        ]);
        $status = "1";
                        $message = "Manufacturer added successfully";
        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
    }

    public function edit(Request $request,$id){
        $make = Manufacturer::whereId($id)->first();
        return view('admin.manufacturer.edit',compact('make')); 
    }

    public function update(Request $request,$id){
        Manufacturer::whereId($id)->update([
            'name' => $request->name,
            'status' => $request->active,
        ]);
        $status = "1";
        $message = "Manufacturer updated successfully";
        echo json_encode(['status' => $status, 'message' => $message]); 
    }

    public function delete(Request $request,$id){
        $item = Manufacturer::whereId($id)->first();
        $item->delete();
        $status = "1";
        $message = "Manufacturer deleted successfully";
        echo json_encode(['status' => $status, 'message' => $message]); 
    }
}
