<?php

namespace App\Http\Controllers\Admin;

use App\Models\Categories;
use App\Models\Divisions;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Validator;

class Category extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!check_permission('category','View')) {
            abort(404);
        }
        $page_heading = "Category";
        $categories = Categories::where(['deleted' => 0])->orderBy('sort_order', 'asc')->get();
        foreach ($categories as $key => $val) {
            $child = Categories::where(['deleted' => 0,'parent_id' => $val->id])->orderBy('created_at', 'desc')->get();
            $categories[$key]->child = $child;
        }
        foreach ($categories as $key => $val) {
            $parent = Categories::where(['deleted' => 0,'id' => $val->parent_id])->first();

            $categories[$key]->parent = isset($parent['name'])?$parent['name']:'';
        }
        
        return view('admin.category.list', compact('page_heading', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!check_permission('category','Create')) {
            abort(404);
        }
        $page_heading = "Category";
        $mode = "create";
        $id = "";
        $name = "";
        $sort_order = "";
        $parent_id = "";
        $image = "";
        $active = "1";
        $banner_image = "";
        $category = [];
        $divisions = Divisions::where(['deleted' => 0,'active'=>1])->get();
        $categories = Categories::where(['deleted' => 0,'active'=>1, 'parent_id' => 0])->get();
        return view("admin.category.create", compact('page_heading', 'category', 'mode', 'id', 'name','sort_order', 'parent_id', 'image', 'active', 'categories', 'banner_image','divisions'));
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
            $check_exist = Categories::where(['deleted' => 0, 'name' => $request->name, 'parent_id' => $request->parent_id])->where('id', '!=', $request->id)->get()->toArray();
            if (empty($check_exist)) {
                $ins = [
                    'name' => $request->name,
                    'sort_order' => $request->sort_order,
                    'updated_at' => gmdate('Y-m-d H:i:s'),
                    'updated_uid' => session("user_id"),
                    'parent_id' => $request->parent_id ?? 0,
                    'division_id' => $request->division_id ?? 0,
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
                    $category = Categories::find($request->id);
                    $ins['slug'] = Str::slug($request->name);
                    $category->update($ins);
                    $status = "1";
                    $message = "Category updated succesfully";
                } else {
                    $ins['created_uid'] = session("user_id");
                    $ins['created_at'] = gmdate('Y-m-d H:i:s');
                    $ins['slug'] = Str::slug($request->name);
                    Categories::create($ins);
                    $status = "1";
                    $message = "Category added successfully";
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
        if (!check_permission('category','Edit')) {
            abort(404);
        }
        $category = Categories::find($id);
        if ($category) {
            $page_heading = "Category ";
            $mode = "edit";
            $id = $category->id;
            $name = $category->name;
            $sort_order = $category->sort_order;
            $parent_id = $category->parent_id;
            $image = $category->image;
            $division_id = $category->division_id;
            $active = $category->active;
            $banner_image = $category->banner_image;
            $divisions = Divisions::where(['deleted' => 0,'active'=>1])->get();
            $categories = Categories::where(['deleted' => 0, 'parent_id' => 0])->where('id', '!=', $id)->get();
            return view("admin.category.create", compact('page_heading', 'category', 'mode', 'id', 'name','sort_order', 'parent_id', 'image', 'active', 'categories', 'banner_image','divisions','division_id'));
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

        $category_count = Categories::where(['deleted' => 0, 'parent_id' => $id])->count();
        if($category_count){
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
    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
        if (Categories::where('id', $request->id)->update(['active' => $request->status])) {
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

            $list = Categories::where(['deleted' => 0])->orderBy('sort_order', 'asc')->get();
            $back = url("admin/category");
            return view("admin.sort", compact('page_heading', 'list','back'));
        }
    }
}
