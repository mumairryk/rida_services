<?php

namespace App\Http\Controllers\Admin;

use App\Models\Divisions;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Validator;

class Division extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!check_permission('division','View')) {
            abort(404);
        }
        $page_heading = "Division";
        $divisions = Divisions::where(['deleted' => 0, 'parent_id' => 0])->orderBy('sort_order', 'asc')->get();
        foreach ($divisions as $key => $val) {
            $child = Divisions::where(['deleted' => 0,'parent_id' => $val->id])->orderBy('created_at', 'desc')->get();
            $divisions[$key]->child = $child;
        }
        return view('admin.division.list', compact('page_heading', 'divisions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!check_permission('division','Create')) {
            abort(404);
        }
        $page_heading = "Division";
        $mode = "create";
        $id = "";
        $name = "";
        $sort_order = "";
        $parent_id = "";
        $image = "";
        $active = "1";
        $banner_image = "";
        $category = [];
        $categories = Divisions::where(['deleted' => 0,'active'=>1, 'parent_id' => 0])->get();
        return view("admin.division.create", compact('page_heading', 'category', 'mode', 'id', 'name','sort_order', 'parent_id', 'image', 'active', 'categories', 'banner_image'));
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
            $check_exist = Divisions::where(['deleted' => 0, 'name' => $request->name, 'parent_id' => $request->parent_id])->where('id', '!=', $request->id)->get()->toArray();
            if (empty($check_exist)) {
                $ins = [
                    'name' => $request->name,
                    'sort_order' => $request->sort_order,
                    'updated_at' => gmdate('Y-m-d H:i:s'),
                    'updated_uid' => session("user_id"),
                    'parent_id' => $request->parent_id ?? 0,
                    'active' => $request->active,
                ];

                if($request->file("image")){
                    $response = image_upload($request,'category','image');
                    if($response['status']){
                        $ins['image'] = $response['link'];
                    }
                }
                if($request->file("banner_image")){
                    $response = image_upload($request,'category','banner_image');
                    if($response['status']){
                        $ins['banner_image'] = $response['link'];
                    }
                }
                if ($request->id != "") {
                    $division = Divisions::find($request->id);
                    $ins['slug'] = Str::slug($request->name);
                    $division->update($ins);
                    $status = "1";
                    $message = "Division updated succesfully";
                } else {
                    $ins['created_uid'] = session("user_id");
                    $ins['created_at'] = gmdate('Y-m-d H:i:s');
                    $ins['slug'] = Str::slug($request->name);
                    Divisions::create($ins);
                    $status = "1";
                    $message = "Division added successfully";
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!check_permission('division','Edit')) {
            abort(404);
        }
        $division = Divisions::find($id);
        if ($division) {
            $page_heading = "Category ";
            $mode = "edit";
            $id = $division->id;
            $name = $division->name;
            $sort_order = $division->sort_order;
            $parent_id = $division->parent_id;
            $image = $division->image;
            $active = $division->active;
            $banner_image = $division->banner_image;
            $divisions = Divisions::where(['deleted' => 0, 'parent_id' => 0])->where('id', '!=', $id)->get();
            return view("admin.division.create", compact('page_heading', 'division', 'mode', 'id', 'name','sort_order', 'parent_id', 'image', 'active', 'divisions', 'banner_image'));
        } else {
            abort(404);
        }
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

        $division_count = Divisions::where(['deleted' => 0, 'parent_id' => $id])->count();
        if($division_count){
            $message = "Sorry!.. You can't delete this parent category. First delete it's subcategories";
            echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
            die();
        }else{
            $where['product.deleted'] = 0;
            $where['product_category.category_id'] = $id;
            $category_count = DB::table('product_category')->join('product','product.id','product_category.product_id')->where($where)->count();  
            if($category_count){
                $message = "Sorry!.. You can't delete this.There are products under this category";
                echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
                die();
            }
        }
        $category = Categories::find($id);
        if ($category) {
            $category->deleted = 1;
            $category->active = 0;
            $category->updated_at = gmdate('Y-m-d H:i:s');
            $category->updated_uid = session("user_id");
            $category->save();
            $status = "1";
            $message = "Category removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);

    }
}