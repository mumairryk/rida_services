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
use App\Models\UserVehicle;
use Illuminate\Http\Request;
use Validator;

class DeliveryRepresentativeController  extends Controller
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
        $page_heading = "Delivery Representative";
        $datamain = VendorModel::select('*', 'users.name as name', 'industry_types.name as industry', 'users.active as active', 'users.id as id', 'users.updated_at as updated_at')
            ->where(['user_type_id' => '6', 'users.deleted' => '0'])
            //->with('vendordata')
            ->leftjoin('vendor_details', 'vendor_details.user_id', '=', 'users.id')
            ->leftjoin('industry_types', 'industry_types.id', '=', 'vendor_details.industry_type')
            ->orderBy('users.id', 'desc')->get();

        return view('admin.delivery_representative.list', compact('page_heading', 'datamain'));
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
        $page_heading = "Delivery Representative";
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
        $industry = IndustryTypes::where(['deleted' => 0])->get();
        $accountTypes = AccountType::where(['deleted' => 0])->get();
        $banks = BankModel::get();
        $banks_codes = BankCodetypes::get();
        $business_infos = PublicBusinessInfos::where(['deleted' => 0, 'active' => 1])->get();
        return view("admin.delivery_representative.create", compact('page_heading', 'industry', 'accountTypes', 'id', 'name', 'dial_code', 'active', 'prefix', 'countries', 'states', 'cities', 'banks', 'banks_codes', 'business_infos'));
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
            $luser_name = strtolower($request->user_name);
            $check_user_name_exist = VendorModel::whereRaw("LOWER(user_name) = '$luser_name'")->where('id', '!=', $request->id)->get()->toArray();
            if ($check_user_name_exist) {
                $status = "0";
                $message = "Username should be unique";
                $errors['user_name'] = "Already exist";
                echo json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
                die();
            }

            $check_exist = VendorModel::whereRaw("LOWER(email) = '$lemail'")->where('id', '!=', $request->id)->get()->toArray();
            if (empty($check_exist)) {
                $check_exist_phone = VendorModel::where('phone', $request->phone)->where('id', '!=', $request->id)->get()->toArray();
                if (empty($check_exist_phone)) {

                    $ins = [
                        'name' => $request->name,
                        'email' => $request->email,
                        'dial_code' => $request->dial_code,
                        'res_dial_code' => $request->res_dial_code,
                        'res_phone' => $request->res_phone,
                        'country_id' => $request->country_id,
                        'phone' => $request->phone,
                        'dob' => $request->dob,
                        'role' => '2', //customer
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'user_name' => $request->name,
                        'user_type_id' => 6,
                        'activity_type_id' => $request->activity_type_id ?? 0,
                        'address' => $request->address,
                    ];

                    $lat = "";
                    $long = "";
                    $location_name = $request->txt_location;
                    if ($request->location) {
                        $location = explode(",", $request->location);
                        $lat = $location[0];
                        $long = $location[1];
                    }

                    $vehicle = [
                        'vehicle_type' => $request->vehicle_type,
                    ];

                    if ($request->password) {
                        $ins['password'] = bcrypt($request->password);
                    }

                    if ($request->file("user_image")) {
                        $response = image_upload($request, 'company', 'user_image');
                        if ($response['status']) {
                            $ins['user_image'] = $response['link'];
                        }
                    }

                    if ($request->file("vehicle_front")) {
                        $response = image_upload($request, 'vehicle', 'vehicle_front');
                        if ($response['status']) {
                            $vehicle['vehicle_front'] = $response['link'];
                        }
                    }

                    if ($request->file("vehicle_back")) {
                        $response = image_upload($request, 'company', 'vehicle_back');
                        if ($response['status']) {
                            $vehicle['vehicle_back'] = $response['link'];
                        }
                    }

                    if ($request->file("vehicle_registration")) {
                        $response = image_upload($request, 'company', 'vehicle_registration');
                        if ($response['status']) {
                            $vehicle['vehicle_registration'] = $response['link'];
                        }
                    }

                    if ($request->file("driving_license")) {
                        $response = image_upload($request, 'company', 'driving_license');
                        if ($response['status']) {
                            $vehicle['driving_license'] = $response['link'];
                        }
                    }

                    if ($request->id != "") {
                        $ins['updated_at'] = gmdate('Y-m-d H:i:s');
                        $user = VendorModel::find($request->id);
                        $user->update($ins);

                        $vendordata = VendorDetailsModel::where('user_id', $request->id)->first();
                        if (empty($vendordata->id)) {
                            $vendordatils = new VendorDetailsModel();
                            $vendordatils->user_id = $request->id;
                        } else {
                            $vendordatils = VendorDetailsModel::find($vendordata->id);
                        }
                        $bankdata = BankdataModel::where('user_id', $request->id)->first();
                        if ($bankdata) {
                            $bankdata->bank_name = $request->bank_name;
                            $bankdata->account_no = $request->account_no;
                            $bankdata->iban_code = $request->iban_code;
                            $bankdata->save();
                        } else {
                            $bankdata = new BankdataModel();
                            $bankdata->bank_name = $request->bank_name;
                            $bankdata->account_no = $request->account_no;
                            $bankdata->iban_code = $request->iban_code;
                            $bankdata->user_id = $request->id;
                            $bankdata->save();
                        }

                        $vehicle['updated_at'] = gmdate('Y-m-d H:i:s');
                        $userVehicle = UserVehicle::where('user_id', $request->id)->first();
                        $userVehicle->update($vehicle);

                        $loc = \App\Models\UserLocations::where('user_id', $request->id)->first();
                        if(!$loc){
                            $loc = new \App\Models\UserLocations();
                            $loc->user_id = $request->id;
                        }
                        $loc->lattitude = $lat;
                        $loc->longitude = $long;
                        $loc->location_name = $location_name;
                        $loc->updated_at = gmdate('Y-m-d H:i:s');
                        $loc->save();

                        $status = "1";
                        $message = "Delivery Representative updated succesfully";
                    } else {
                        $ins['created_at'] = gmdate('Y-m-d H:i:s');
                        $userid = VendorModel::create($ins)->id;

                        $loc = new \App\Models\UserLocations();
                        $loc->user_id = $userid;
                        $loc->lattitude = $lat;
                        $loc->longitude = $long;
                        $loc->location_name = $location_name;
                        $loc->created_at = gmdate('Y-m-d H:i:s');
                        $loc->save();

                        $vehicle['user_id'] = $userid;
                        $vehicle['created_at'] = gmdate('Y-m-d H:i:s');

                        $vendordatils = new VendorDetailsModel();
                        $vendordatils->user_id = $userid;
                        $vendordatils->updated_at = gmdate('Y-m-d H:i:s');

                        $bankdata = new BankdataModel();
                        $bankdata->bank_name = $request->bank_name;
                        $bankdata->account_no = $request->account_no;
                        $bankdata->iban_code = $request->iban_code;
                        $bankdata->user_id = $userid;
                        $bankdata->save();


                        $vehicle_id = UserVehicle::create($vehicle)->id;


                        $status = "1";
                        $message = "Delivery Representative added successfully";
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
        $page_heading = "Edit Delivery Representative";
        $datamain = VendorModel::find($id);
        if (!$datamain) {
            abort(404);
        }
        $datamain->vendordatils = VendorDetailsModel::where('user_id', $id)->first();
        // $datamain->bankdetails = BankdataModel::where('user_id', $id)->first();

        $countries = CountryModel::orderBy('name', 'asc')->get();
        $industry = IndustryTypes::where(['deleted' => 0])->get();
        $banks = BankModel::get();
        $banks_codes = BankCodetypes::get();
        $user_image = asset($datamain->user_image);
        $states = States::where(['deleted' => 0, 'active' => 1, 'country_id' => $datamain->country_id])->orderBy('name', 'asc')->get();
        $accountTypes = AccountType::where(['deleted' => 0])->get();

        $datamain->bankdetails = BankdataModel::where('user_id', $id)->first();
        $cities = Cities::where(['deleted' => 0, 'active' => 1, 'state_id' => $datamain->state_id])->orderBy('name', 'asc')->get();
        $business_infos = PublicBusinessInfos::where(['deleted' => 0, 'active' => 1])->get();
        $userVehicle = UserVehicle::where(['deleted' => 0, 'user_id' => $id])->first();


        if ($datamain) {
            $location = \App\Models\UserLocations::where('user_id',$id)->first();
            return view("admin.delivery_representative.create", compact('page_heading', 'datamain', 'accountTypes', 'userVehicle', 'id', 'countries', 'states', 'cities', 'user_image', 'industry', 'banks', 'banks_codes', 'business_infos','location'));
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
            $message = "Delivery Representative removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
    }
}