<?php

namespace App\Http\Controllers\admin;

use App\Models\FoodCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Validator;

class FoodCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!check_permission('food_category', 'View')) {
            abort(404);
        }
        $page_heading = "Food Category";
        $categories = FoodCategory::where(['deleted' => 0, 'parent_id' => 0])->orderBy('sort_order', 'asc')->get();
        foreach ($categories as $key => $val) {
            $child = FoodCategory::where(['deleted' => 0, 'parent_id' => $val->id])->orderBy('created_at', 'desc')->get();
            $categories[$key]->child = $child;
        }
        return view('admin.food_category.list', compact('page_heading', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!check_permission('food_category', 'Create')) {
            abort(404);
        }
        $page_heading = "Food Category";
        $mode = "create";
        $id = "";
        $name = "";
        $parent_id = "";
        $image = "";
        $active = "1";
        $banner_image = "";
        $category = [];
        $categories = FoodCategory::where(['deleted' => 0, 'active' => 1, 'parent_id' => 0])->get();
        return view("admin.food_category.create", compact('page_heading', 'category', 'mode', 'id', 'name', 'parent_id', 'image', 'active', 'categories', 'banner_image'));
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
            $check_exist = FoodCategory::where(['deleted' => 0, 'name' => $request->name, 'parent_id' => $request->parent_id])->where('id', '!=', $request->id)->get()->toArray();
            if (empty($check_exist)) {
                $ins = [
                    'name' => $request->name,
                    'updated_at' => gmdate('Y-m-d H:i:s'),
                    'updated_uid' => session("user_id"),
                    'parent_id' => $request->parent_id ?? 0,
                    'active' => $request->active,
                ];

                if ($request->file("image")) {
                    $response = image_upload($request, 'food_category', 'image');
                    if ($response['status']) {
                        $ins['image'] = $response['link'];
                    }
                }
                if ($request->file("banner_image")) {
                    $response = image_upload($request, 'food_category', 'banner_image');
                    if ($response['status']) {
                        $ins['banner_image'] = $response['link'];
                    }
                }
                if ($request->id != "") {
                    $category = FoodCategory::find($request->id);
                    $category->update($ins);
                    $status = "1";
                    $message = "Food Category updated succesfully";
                } else {
                    $ins['created_uid'] = session("user_id");
                    $ins['created_at'] = gmdate('Y-m-d H:i:s');
                    FoodCategory::create($ins);
                    $status = "1";
                    $message = "Food Category added successfully";
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
        if (!check_permission('food_category', 'Edit')) {
            abort(404);
        }
        $category = FoodCategory::find($id);
        if ($category) {
            $page_heading = "Food Category";
            $mode = "edit";
            $id = $category->id;
            $name = $category->name;
            $parent_id = $category->parent_id;
            $image = $category->image;
            $active = $category->active;
            $banner_image = $category->banner_image;
            $categories = FoodCategory::where(['deleted' => 0, 'parent_id' => 0])->where('id', '!=', $id)->get();
            return view("admin.food_category.create", compact('page_heading', 'category', 'mode', 'id', 'name', 'parent_id', 'image', 'active', 'categories', 'banner_image'));
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

        $category_count = FoodCategory::where(['deleted' => 0, 'parent_id' => $id])->count();
        if ($category_count) {
            $message = "Sorry!.. You can't delete this parent category. First delete it's subcategories";
            echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
            die();
        } else {
            $where['product.deleted'] = 0;
            $where['product_category.category_id'] = $id;
            $category_count = DB::table('product_category')->join('product', 'product.id', 'product_category.product_id')->where($where)->count();
            if ($category_count) {
                $message = "Sorry!.. You can't delete this.There are products under this category";
                echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
                die();
            }
        }
        $category = FoodCategory::find($id);
        if ($category) {
            $category->deleted = 1;
            $category->active = 0;
            $category->updated_at = gmdate('Y-m-d H:i:s');
            $category->updated_uid = session("user_id");
            $category->save();
            $status = "1";
            $message = "Food Category removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
    }
    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
        if (FoodCategory::where('id', $request->id)->update(['active' => $request->status])) {
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
            $sorted = FoodCategory::sort_item($items);
            if ($sorted) {
                $status = 1;
            }

            echo json_encode(['status' => $status, 'message' => $message]);
        } else {
            $page_heading = "Sort Food Categories";

            $list = FoodCategory::where(['deleted' => 0, 'parent_id' => 0])->orderBy('sort_order', 'asc')->get();
            $back = url("admin/food_category");
            return view("admin.sort", compact('page_heading', 'list', 'back'));
        }
    }
}
