<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\MenuItemType;
use App\Models\User;
use App\Models\VendorModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    function menu()
    {
        $vendor_id = \request()->get('vendor');
        $user = VendorModel::where('id', $vendor_id)->first();
        $menu = Menu::with('items')->where('vendor_id', $vendor_id)->first();
        return view('admin.wholeSellers.menu', compact('user', 'menu'));
    }

    function updateMenu(Request $request){
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'about' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
        } else {
            Menu::updateOrCreate(['vendor_id' => $request->user_id], ['about' => $request->about]);
            $status = "1";
            $message = "Info updated successfully";
        }
        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
    }
    function addItem($user_id)
    {
        $user = VendorModel::where('id', $user_id)->first();
        $itemTypes = MenuItemType::where('is_active', 1)->get();
        $id=null;
        return view('admin.wholeSellers.add-menu-item', compact('user', 'itemTypes','id'));
    }

    function editItem($id){
        $item = MenuItem::where('id', $id)->first();
        $user = VendorModel::where('id', $item->menu->vendor_id)->first();
        $itemTypes = MenuItemType::where('is_active', 1)->get();
        return view('admin.wholeSellers.add-menu-item', compact('user', 'itemTypes','item','id'));
    }

    function saveItem(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $redirectUrl = '';

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'image' => 'required',
            'description' => 'nullable',
            'menu_item_type_id' => 'required',
            'price' => 'required',
            'quantity' => 'required',

            // 'last_name' => 'required',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $menu = Menu::where('vendor_id', $request->user_id)->first();
            if (!$menu) {
                $menu = Menu::create([
                    'vendor_id' => $request->user_id,
                    'about' => 'Add about here...',
                    'subtitle' => 'Add subtitle here...',
                ]);
            }
            $title = strtolower($request->title);
            $check_title_exist = MenuItem::whereRaw("LOWER(title) = '$title'")
                ->where('id', '!=', $request->id)
                ->where('menu_id', '=', $menu->id)->get()->toArray();
            if ($check_title_exist) {
                $status = "0";
                $message = "Title already exist";
                $errors['title'] = "Already exist";
                echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
                die();
            }

            $ins = [
                'menu_id' => $menu->id,
                'menu_item_type_id' => $request->menu_item_type_id,
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'quantity' => $request->quantity,
            ];

            if ($request->file("image")) {
                if ($request->id != "") {
                    //Delete previous image
                    $menuItem = MenuItem::find($request->id);
                    deleteFile($menuItem->image);
                }
                $response = image_upload($request, 'menuItem', 'image');
                if ($response['status']) {
                    $ins['image'] = $response['link'];
                }
            }


            if ($request->id != "") {
                $ins['updated_at'] = gmdate('Y-m-d H:i:s');
                $menuItem = MenuItem::find($request->id);
                $menuItem->update($ins);


                $status = "1";
                $message = "Menu Item updated successfully";
            } else {
                $ins['created_at'] = gmdate('Y-m-d H:i:s');
                MenuItem::create($ins)->id;
                $status = "1";
                $message = "Menu Item added successfully";
            }


        }
        echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
    }
}
