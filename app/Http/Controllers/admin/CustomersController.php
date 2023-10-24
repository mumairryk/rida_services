<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankCodetypes;
use App\Models\BankdataModel;
use App\Models\BankModel;
use App\Models\Cities;
use App\Models\CountryModel;
use App\Models\IndustryTypes;
use App\Models\States;
use App\Models\VendorDetailsModel;
use App\Models\VendorModel;
use App\Models\BlockUser;
use App\Models\UserReport;
use App\Models\PublicBusinessInfos;
use App\Models\AccountType;
use Illuminate\Http\Request;
use Validator;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!check_permission('customers', 'View')) {
            abort(404);
        }
        $page_heading = "Customers";
        $datamain = VendorModel::select('*', 'users.name as name', 'users.active as active', 'users.id as id', 'users.updated_at as updated_at')
            ->where(['role' => '2', 'users.deleted' => '0','users.phone_verified' => '1'])
            //->with('vendordata')
            ->orderBy('users.id', 'desc')->get();

        return view('admin.customer.list', compact('page_heading', 'datamain'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!check_permission('customers', 'Create')) {
            abort(404);
        }
        $page_heading = "Customers";
        $mode = "create";
        $id = "";
        $prefix = "";
        $name = "";
        $dial_code = "";
        $image = "";
        $active = "1";
        $states = [];
        $cities = [];
        $countries = CountryModel::orderBy('name', 'asc')->get();
        
        return view("admin.customer.create", compact('page_heading',  'id', 'name', 'dial_code', 'active', 'prefix', 'countries'));
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
            'phone' => 'required',
            'email' => 'required|email',

            // 'last_name' => 'required',
        ]);
        if (!empty($request->password)) {
            $validator = Validator::make($request->all(), [
                'confirm_password' => 'required',
            ]);
        }
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $input = $request->all();

            $lemail = strtolower($request->email);            
            $check_exist = VendorModel::whereRaw("LOWER(email) = '$lemail'")->where('id', '!=', $request->id)->get()->toArray();
            if (empty($check_exist)) {
                $check_exist_phone = VendorModel::where('phone', $request->phone)->where('id', '!=', $request->id)->get()->toArray();
                if (empty($check_exist_phone)) {

                    $ins = [
                        'name' => $request->name,
                        'email' => $request->email,
                        'dial_code' => $request->dial_code,
                        'phone' => $request->phone,
                        'role' => '2', //customer
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'user_name' => $request->name,
                    ];

                    if ($request->password) {
                        $ins['password'] = bcrypt($request->password);
                    }
                    if ($file = $request->file("logo")) {
                        $dir = config('global.upload_path') . "/" . config('global.user_image_upload_dir');
                        $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
                        $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
                        $ins['user_image'] = $file_name;
                    }

                    if ($request->id != "") {
                        $ins['updated_at'] = gmdate('Y-m-d H:i:s');
                        $user = VendorModel::find($request->id);
                        $user->update($ins);
                        $status = "1";
                        $message = "Customer updated succesfully";
                    } else {
                        $ins['created_at'] = gmdate('Y-m-d H:i:s');
                        $userid = VendorModel::create($ins)->id;
                        $status = "1";
                        $message = "Customer added successfully";
                    }
                } else {
                    $status = "0";
                    $message = "Phone number should be unique";
                    $errors['phone'] = "Already exist";
                }
            } else {
                $status = "0";
                $message = "Email should be unique";
                $errors['email'] = $request->email . " already added";
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
        if (!check_permission('customers', 'Edit')) {
            abort(404);
        }
        $page_heading = "Edit Customer";
        $datamain = VendorModel::find($id);
        if (!$datamain) {
            abort(404);
        }
        $countries = CountryModel::orderBy('name', 'asc')->get();
        $user_image = asset($datamain->user_image);
        if ($datamain) {
            return view("admin.customer.create", compact('page_heading', 'datamain', 'id', 'countries', 'user_image'));
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
    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
        if (VendorModel::where('id', $request->id)->update(['active' => $request->status])) {
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
    public function verify(Request $request)
    {
        $status = "0";
        $message = "";
        if (VendorModel::where('id', $request->id)->update(['verified' => $request->status])) {
            $status = "1";
            $msg = "Successfully verified";
            if (!$request->status) {
                $msg = "Successfully updated";
            }
            $message = $msg;
        } else {
            $message = "Something went wrong";
        }
        echo json_encode(['status' => $status, 'message' => $message]);
    }
    public function destroy($id)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $datatb = VendorModel::find($id);
        if ($datatb) {
            $datatb->deleted = 1;
            $datatb->active = 0;
            $datatb->save();
            $status = "1";
            $message = "Customer removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
    }
}