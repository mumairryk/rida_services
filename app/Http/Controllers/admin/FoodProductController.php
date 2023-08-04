<?php

namespace App\Http\Controllers\admin;

use Validator;
use App\Models\User;
use App\Models\Brands;
use App\Models\FoodItems;
use App\Models\FoodHeading;
use App\Models\FoodProduct;
use App\Models\VendorModel;
use App\Models\FoodCategory;
use Illuminate\Http\Request;
use App\Models\FoodMenuProduct;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\FoodCategoryProduct;
use App\Http\Controllers\Controller;

class FoodProductController extends Controller
{

    public function index(Request $request, $store_id = null)
    {

        if (!check_permission('food_products', 'View')) {
            abort(404);
        }
        $page_heading = "Food Products";
        $filter = ['food_products.deleted' => 0];
        $sortby = "food_products.id";
        $sort_order = "desc";

        if (isset($_GET['sort_type']) && $_GET['sort_type'] != "") {
            if ($_GET['sort_type'] == 1) {
                $sortby = "food_products.product_name";
                $sort_order = "asc";
            } else if ($_GET['sort_type'] == 2) {
                $sortby = "food_products.product_name";
                $sort_order = "desc";
            } else if ($_GET['sort_type'] == 3) {
                $sortby = "food_products.id";
                $sort_order = "asc";
            } else if ($_GET['sort_type'] == 4) {
                $sortby = "food_products.id";
                $sort_order = "desc";
            } else if ($_GET['sort_type'] == 5) {
                $sortby = "food_products.updated_at";
                $sort_order = "asc";
            } else if ($_GET['sort_type'] == 6) {
                $sortby = "food_products.updated_at";
                $sort_order = "desc";
            }
        }

        // $params = [];
        // $category_ids = [];
        $search_key = $request->search_key;
        $from = isset($_GET['from']) ? $_GET['from'] : '';
        $to = isset($_GET['to']) ? $_GET['to'] : '';
        $category_ids[0] = $request->category;
        $category = $request->category;
        // $store = isset($_GET['store']) ? $_GET['store'] : '';
        // $vendor = isset($_GET['vendor']) ? $_GET['vendor'] : '';
        // $params['from'] = $from;
        // $params['to'] = $to;
        // $params['store'] = $store;
        // $params['vendor'] = $vendor;

        // if ($store_id != null) {
        //     $params['store_id'] = $store_id;
        // }

        $foodProducts = FoodProduct::query();

        if ($request->filled('store_id')) {
            $foodProducts->where('store_id', $request->store_id);
        }

        if ($request->filled('category')) {
            $foodProducts->whereHas('food_category_products', function ($query) use ($request) {
                $query->where('food_category_id', $request->category);
            });
        }

        if ($request->filled('search_key')) {
            $filter['food_products.product_name'] = ['ilike', '%' . $request->search_key . '%'];
        }

        $list = $foodProducts->where($filter)->orderBy($sortby, $sort_order)->paginate(10);

        $parent_categories_list = $parent_categories = FoodCategory::where(['deleted' => 0, 'active' => 1, 'parent_id' => 0])->get()->toArray();
        $parent_categories_list = FoodCategory::where(['deleted' => 0, 'active' => 1])->where('parent_id', '!=', 0)->get()->toArray();

        $parent_categories = array_column($parent_categories, 'name', 'id');
        asort($parent_categories);
        $category_list = $parent_categories;

        $sub_categories = [];
        foreach ($parent_categories_list as $row) {
            $sub_categories[$row['parent_id']][$row['id']] = $row['name'];
        }
        $sub_category_list = $sub_categories;


        return view("admin.food_product.index", compact("page_heading", "list", "search_key", 'category_list', 'sub_category_list', 'from', 'to', 'category_ids', 'category', 'store_id'));
    }

    public function create($id = "")
    {

        if ($id != "" && $id > 0) {
            if (!check_permission('food_products', 'Edit')) {
                abort(404);
            }
        } else {
            if (!check_permission('food_products', 'Create')) {
                abort(404);
            }
        }

        $page_heading = "Create Food Product";

        $headings = FoodHeading::where(['deleted' => 0, 'active' => 1])->get();
        $foodItems = FoodItems::where(['deleted' => 0, 'active' => 1])->get();
        $stores = User::select('id', 'name as store_name')->Where(['deleted' => 0, 'active' => 1, 'role' => 2])->get();
        $sellers = VendorModel::select('users.id', 'name')->where('role', '3')->get();
        $brand   = Brands::where(['brand.deleted' => 0])->get();
        $categories = FoodCategory::select('id', 'name')->orderBy('sort_order', 'asc')->where(['deleted' => 0, 'active' => 1, 'parent_id' => 0])->get();
        $category_ids = [];
        $menu_ids = [];
        $product = new FoodProduct();
        $product_images = [];
        foreach ($categories as $key => $val) {
            $categories[$key]->sub = FoodCategory::select('id', 'name')->orderBy('sort_order', 'asc')->where(['deleted' => 0, 'active' => 1, 'parent_id' => $val->id])->get();
        }

        $mode = 'create';

        if ($id != "" && $id > 0) {
            $product = FoodProduct::with(['foodProductCombo.combo_items'])->find($id);
            $category_ids = FoodCategoryProduct::where('food_product_id', $id)->pluck('food_category_id')->toArray();
            $menu_ids = FoodMenuProduct::where('food_product_id', $id)->pluck('food_menu_id')->toArray();
            $product_images = $product->product_images;
            $mode = 'edit';
        }

        $readonly = false;
        $itemsCounter = [];
        foreach ($product->foodProductCombo as $key => $item) {
            $itemsCounter[$key] = $item->combo_items->count();
        }
        $itemsCounter = json_encode($itemsCounter);

        return view('admin.food_product.create', compact('itemsCounter', 'mode', 'headings', 'foodItems', 'page_heading', 'menu_ids', 'categories', 'category_ids', 'id', 'product', 'stores', 'sellers', 'brand', 'readonly'));
    }

    public function add_product(Request $request)
    {

        $status  = "0";
        $message = "";
        $errors  = [];
        $id      = $request->id;

        $rules = [
            // 'vendor_id' => 'required|exists:users,id',
            // 'shared_product' => 'nullable|boolean',
            'store_id' => 'required|exists:users,id',
            // 'is_editable_by_outlets' => 'required_if:shared_product,1|boolean',
            'product_name' => 'required|string|max:255',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0|lte:regular_price',
            'pieces' => 'nullable|integer|min:0',
            'is_veg' => ['required', Rule::in([0, 1, 2])],
            'promotion' => ['nullable', Rule::in([0, 1, 2])],
            'product_images' => 'nullable|array',
            'product_images.*' => 'required|image|max:2048',
            'description' => 'required|string|max:1000',
            'category_ids' => 'required|array',
            'menu_ids' => 'required|array',

            'food_heading_id' => 'sometimes|array',
            'food_heading_id.*' => 'required_if:food_heading_id,array|exists:food_headings,id',
            'is_required' => 'sometimes|array',
            'is_required.*' => 'required_if:is_required,array|boolean',

            'min_select' => 'sometimes|array',
            'max_select' => 'sometimes|array',

            'food_item_id' => 'sometimes|array',
            'is_default' => 'sometimes|array',
            'extra_price' => 'sometimes|array',

            'image_counter' => 'nullable',

        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                'category_ids.required' => 'Category required',
                'product_images.image' => 'should be in image format (.jpg,.jpeg,.png)',
                'product_images.max' => 'max size allowed is 2MB',
                'food_heading_id.*.required_if' => 'Heading required',
                'food_heading_id.*.exists' => 'Heading not found',
            ]
        );

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();

            echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
            return;
        }

        $validatedData = $validator->validated();

        DB::beginTransaction();

        try {
            $res = FoodProduct::manageFoodProduct($validatedData, $id, $request);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $res = ['status' => 0, 'message' => $th->getMessage(), 'errors' => []];
        }

        echo json_encode($res);
    }

    function change_status(Request $request)
    {
        $status  = "0";
        $message = "";
        if (FoodProduct::where('id', $request->id)->update(['product_status' => $request->status])) {
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

    public function delete_product($id = '')
    {
        $check = false; //Common::check_already('order_products', ['product_id' => $id, 'product_type' => '1']);
        $status = 0;
        if ($check != 1) {
            FoodProduct::where('id', $id)->update(['deleted' => 1]);
            $status = "1";
            $message = "Product removed successfully";
        } else {
            $message = "Unable to delete child details exist";
        }

        echo json_encode(['status' => $status, 'message' => $message]);
    }

    public function comboRow(Request $request)
    {
        $headings = FoodHeading::where(['deleted' => 0, 'active' => 1])->get();
        $counter = $request->counter;
        $COUNT = $counter + 1;
        $res = '';
        $mode = 'create';

        if ($request->filled('product_id')) {
            $product = FoodProduct::with(['foodProductCombo.combo_items'])->find($request->product_id);
            $foodProductCombos = $product->foodProductCombo;
            $COUNT = 1;
            $mode = 'edit';
        } else {
            $foodProductCombos[] = null;
        }

        foreach ($foodProductCombos as $index => $heading) {
            if ($mode == 'edit') {
                $heading_id = $heading->food_heading_id;
                $COUNT = $index + 1;
                $counter = $index;
                $isRequiredSelected = $heading->is_required ? 'selected' : '';
                $minSelection = $heading->min_select ? $heading->min_select : '1';
                $maxSelection = $heading->max_select ? $heading->max_select : '1';
                $comboItems = $heading->combo_items;
            } else {
                $heading_id = '';
                $isRequiredSelected = '';
                $minSelection = '1';
                $maxSelection = '1';
                $comboItems[] = null;
            }

            $res .= '
            <div class="jumbotron p-2" data-role="combo-block" data-block-id="">
                        <div class="d-flex justify-content-between">
                            <div>
                                <legend>#' . $COUNT . '</legend>
                            </div>
                            <div class="ml-auto">
                                <a href="javascript:;" class="btn btn-sm btn-danger remove-mini-btn"
                                    data-role="delete-combo-block"><i class="flaticon-delete-fill"></i>
                                    <small>Remove</small></a>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <div class="col-sm-3">
                                <label class="col-form-label d-flex align-items-center justify-content-between">Heading <a
                                        href="#" class="text-primary" data-toggle="modal" data-target="#modal-add-combo-attribute"
                                        data-company="">Add New</a></label>
                                <select class="form-control jqv-input select2" name="food_heading_id[' . $counter . ']" id="food_heading_id' . $counter . '"
                                    data-parsley-required="true" data-role="select2-heading">
                                    <option value="">Select an option</option>';
            foreach ($headings as $item) {
                $selected = $item->id == $heading_id ? 'selected' : '';
                $res .= "<option data-heading-id='$heading_id' $selected value='$item->id'>$item->name</option>";
            }
            $res .= "</select>
                            </div>
                            <div class='col-sm-3'>
                                <label class='col-form-label'>Is Required?</label>
                                <select class='form-control jqv-input select2 is_required' name='is_required[$counter]'
                                    id='is_required' data-parsley-required='false'>
                                    <option value='0'>No</option>
                                    <option $isRequiredSelected value='1'>Yes</option>
                                </select>
                            </div>
                            <div class='combo_quantity col-sm-6 row' style='display:none'>
                                <div class='col-sm-6' data-min-max='2' >
                                    <label class='col-form-label'>Min. Selection</label>
                                    <input type='number' class='form-control form-control-sm jqv-input'
                                        name='min_select[$counter]' value='$minSelection' data-jqv-required='true'
                                        data-jqv-number='true' data-jqv-min='1' min='1' />
                                </div>
                                <div class='col-sm-6' data-min-max='2' >
                                    <label class='col-form-label'>Max. Selection</label>
                                    <input type='number' class='form-control form-control-sm jqv-input'
                                        name='max_select[$counter]' value='$maxSelection' data-jqv-required='true'
                                        data-jqv-number='true' data-jqv-min='1' min='1' />
                                </div>
                            </div>
                        </div>
                        <hr />
                        <table class='table table-sm' data-role='combo-lines'>
                            <thead>
                                <tr>
                                    <td scope='col' class='text-center' style='width:5%'>#</td>
                                    <td scope='col' style='width:45%'>
                                    <label class='col-form-label d-flex align-items-center justify-content-between'>
                                        Items <a class='text-primary' data-toggle='modal' data-target='#modal-add-item-attribute'>Add New</a>
                                    </label>                                
                                    </td>
                                    <td style='width: 5%'></td>
                                    <td scope='col' style='width:15%'>Select by Default</td>
                                    <td style='width: 10%'></td>
                                    <td scope='col'>Extra Price</td>
                                    <td scope='col'></td>
                                </tr>
                            </thead>
                            <tbody>";
            $res .= $this->itemRow($request, $counter, 0, $comboItems, $mode);
            $res .= "</tbody>
                        </table>
                        <div class='text-right pt-3 pr-3'>
                            <button type='button' class='btn btn-primary btn-mini' data-parent-counter='$counter' data-role='add_item_row'>Add</button>
                        </div>
                    </div>";
        }

        return $res;
    }

    public function itemRow(Request $request, $parentCounter = null, $index = null, $comboItems = array(1), $mode = 'create')
    {
        if (($parentCounter == null || $index == null) && $mode == 'create') {
            $parentCounter = $request->counter;
            $index = $request->item;
        }


        $foodItems = FoodItems::where(['deleted' => 0, 'active' => 1])->get();
        $COUNT = $index + 1;
        $itemRequired = $index == 0 ? 'true' : 'false';
        $res = '';

        foreach ($comboItems as $key => $val) {
            if ($mode == 'edit') {
                $itemDefault = $val->is_default == 1 ? 'checked' : '';
                $itemPrice = $val->extra_price > 0 ? $val->extra_price : '';
                $itemID = $val->food_item_id;
                $COUNT = $key + 1;
                $index = $key;
            } else {
                $itemDefault = '';
                $itemPrice = '';
                $itemID = '';
            }

            $res .= "
            <tr>
                <td scope='row' class='text-center'>
                    $COUNT
                </td>
                <td>
    
                    <select class='form-control select2 select2-products jqv-input'
                        name='food_item_id[$parentCounter][$index]' data-role='select2-items'
                        data-placeholder='Select' data-allow-clear='true'
                        data-parsley-required='$itemRequired'>
                        <option value=''>Select</option>";
            foreach ($foodItems as $item) {
                $selected = $item->id == $itemID ? 'selected' : '';
                $res .= "<option $selected value='$item->id'>$item->name</option>";
            }
            $res .= "</select>
                </td>
                <td style='width: 5%'></td>
                <td class='text-center'>
                    <input type='checkbox' $itemDefault name='is_default[$parentCounter][$index]' value='1' />
                </td>
                <td style='width: 10%'></td>
                <td>
                    <input type='text' class='form-control form-control-sm jqv-input'
                        name='extra_price[$parentCounter][$index]' value='$itemPrice'
                        data-jqv-number='true' />
                </td>
            </tr>
            ";
        }

        return $res;
    }

    public function removeProductImage(Request $request)
    {
        $status = 0;
        $message = "";
        $product_id = (int)$request->product_id;
        $image = (string)$request->image;
        if ($product_id != "" && $image != "") {
            $product = FoodProduct::findOrFail($product_id);
            if (is_null($product)) {
                echo json_encode(compact('status', 'message'));
                return;
            }

            $imageArr = $product->product_images;
            $arValues  = array_values($imageArr);
            $k = array_search($image, $imageArr);

            unset($imageArr[$k]);
            $imageArr = array_unique(array_filter($imageArr));
            $arValues  = array_values($imageArr);
            $product->update(['product_images' => $arValues]);
            $status = 1;
            $message = "Deleted ";
        }
        echo json_encode(compact('status', 'message'));
    }
}
