<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\BankCodetypes;
use App\Models\BankdataModel;
use App\Models\BankModel;
use App\Models\CountryModel;
use App\Models\IndustryTypes;
use App\Models\VendorDetailsModel;
use App\Models\VendorModel;
use App\Models\UserLocations;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class HomeController extends Controller
{
    //
    public function index()
    {
        return redirect('vendor');
        $page_heading = "Home";
        return view('front_end.index', compact('page_heading'));
    }
    public function checkAvailability(Request $request)
    {
        $post = $request->all();
        $field = $post['field'];
        $value = $post[$field];
        $exclude = $request->exclude;
        $count =VendorModel::where($field, $value);
        if($exclude){
            $count = $count->where($field,'!=',$exclude);
        }
        $count = $count->get()->count();
        if ($count) {
            dd('');
        } else {
            header("HTTP/1.1 200 Ok");
        }
    }


    public function register()
    {
        $page_heading = "Vendor Registration";
        $countries = CountryModel::orderBy('name', 'asc')->get();
        $industry = IndustryTypes::where(['deleted' => 0])->get();
        $banks = BankModel::get();
        $banks_codes = BankCodetypes::get();

        return view('front_end.register', compact('page_heading', 'countries', 'industry', 'banks', 'banks_codes'));
    }

    public function save_vendor(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $redirectUrl = '';

        $validator = Validator::make($request->all(), [
            'name' => 'required',
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
            $check_exist = VendorModel::where('email', $request->email)->where('id', '!=', $request->id)->get()->toArray();
            if (empty($check_exist)) {
                $check_exist_phone = VendorModel::where('phone', $request->phone)->where('id', '!=', $request->id)->get()->toArray();
                if (empty($check_exist_phone)) {

                    $ins = [
                        'country_id' => $request->country_id,
                        'name' => $request->name,
                        'email' => $request->email,
                        'dial_code' => $request->dial_code,
                        'phone' => $request->phone,
                        'role' => '3', //vendor
                        'first_name' => '',
                        'last_name' => '',
                        'state_id' => $request->state_id,
                        'city_id' => $request->city_id,
                    ];

                    if ($request->password) {
                        $ins['password'] = bcrypt($request->password);
                    }

                    if ($request->file("image")) {
                        $response = image_upload($request, 'company', 'image');
                        if ($response['status']) {
                            $ins['user_image'] = $response['link'];
                        }
                    }

                    if ($request->id != "") {
                        $ins['updated_at'] = gmdate('Y-m-d H:i:s');
                        $user = VendorModel::find($request->id);
                        $user->update($ins);

                        $vendordata = VendorDetailsModel::where('user_id', $request->id)->first();
                        $bank = BankdataModel::where('user_id', $request->id)->first();
                        if (empty($vendordata->id)) {
                            $vendordatils = new VendorDetailsModel();
                            $vendordatils->user_id = $request->id;
                        } else {
                            $vendordatils = VendorDetailsModel::find($vendordata->id);
                        }

                        if (empty($bank->id)) {
                            $bankdata = new BankdataModel();
                            $bankdata->user_id = $request->id;
                        } else {
                            $bankdata = BankdataModel::find($bank->id);
                        }

                        $status = "1";
                        $message = "Vendor updated succesfully";
                    } else {
                        $ins['created_at'] = gmdate('Y-m-d H:i:s');
                        $userid = VendorModel::create($ins)->id;

                        $vendordatils = new VendorDetailsModel();
                        $vendordatils->user_id = $userid;

                        $bankdata = new BankdataModel();
                        $bankdata->user_id = $userid;

                        $status = "1";
                        $message = "Vendor added successfully";
                    }

                    $vendordatils->industry_type = $request->industrytype;
                    $vendordatils->homedelivery = $request->has_own_delivery??1;
                    $vendordatils->branches = $request->no_of_branches;
                    $vendordatils->company_name = $request->company_legal_name;
                    $vendordatils->company_brand = $request->company_brand_name;
                    $vendordatils->reg_date = $request->business_registration_date;
                    $vendordatils->trade_license = $request->trade_licene_number;
                    $vendordatils->trade_license_expiry = $request->trade_licene_expiry;
                    $vendordatils->vat_reg_number = $request->vat_registration_number;
                    $vendordatils->vat_reg_expiry = $request->vat_expiry_date;

                    $vendordatils->address1 = $request->address1;
                    $vendordatils->address2 = $request->address2;
                    $vendordatils->street = $request->street;
                    $vendordatils->state = $request->state_id;
                    $vendordatils->city = $request->city_id;
                    $vendordatils->zip = $request->zip;

                    //logo
                    if ($request->file("logo")) {
                        $response = image_upload($request, 'company', 'logo');
                        if ($response['status']) {
                            $vendordatils->logo = $response['link'];
                        }
                    }
                    //logo end
                    $vendordatils->save();

                    $bankdata->bank_name = $request->bank_id;
                    $bankdata->country = $request->bankcountry;
                    $bankdata->company_account = $request->company_account;
                    $bankdata->account_no = $request->bank_account_number;
                    $bankdata->code_type = $request->bank_code_type;
                    $bankdata->branch_code = $request->bank_branch_code;
                    $bankdata->branch_name = $request->branch_name;

                    if ($request->file("bank_statement")) {
                        $response = image_upload($request, 'company', 'bank_statement');
                        if ($response['status']) {
                            $bankdata->bank_statement_doc = $response['link'];
                        }
                    }

                    if ($request->file("credit_card_statement")) {
                        $response = image_upload($request, 'company', 'credit_card_statement');
                        if ($response['status']) {
                            $bankdata->credit_card_sta_doc = $response['link'];
                        }
                    }
                    $bankdata->save();

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

    public function reset_password($id)
    {
        $userdata = VendorModel::where('password_reset_code',$id)->first();
        if($userdata)
        {
            $timenew       = date('Y-m-d H:i:s');
            $cenvertedTime = date('Y-m-d H:i:s',strtotime('+10 minutes',strtotime($userdata->password_reset_time)));
            if($timenew <= $cenvertedTime)
            {
                $page_heading = "Reset Password";
                $id = $id;
                return view('front_end.reset_password', compact('page_heading','id'));
            }
            else
            {
            echo "Link expired";
            }
        }
        else
        {
            echo "Link expired";
        }

    }
    public function new_password(Request $request)
    {
       if ($request->isMethod('post')) {
            $status = "0";
            $message = "";
            $errors = [];
            $validator = Validator::make($request->all(),
                [
                    'password' => 'required',
                    'token' => 'required',
                ],
                [
                    'password.required' => 'Password required',
                    'token.required' => 'User token required',
                ]
            );
            if ($validator->fails()) {
                $status = "0";
                $message = "Validation error occured";
                $errors = $validator->messages();
            } else {
                $userdata = VendorModel::where('password_reset_code',$request->token)->first();
                $new_pswd = $request->password;
                $user_id = $userdata->id;
                    $up['password'] = bcrypt($new_pswd);
                    $up['updated_on'] =gmdate('Y-m-d H:i:s');
                    if(User::update_password($user_id,$new_pswd)){
                        $status = "1";
                        $message = "Password successfully changed";
                        $errors = '';
                    }else{
                        $status = "0";
                        $message = "Unable to change password. Please try again later";
                        $errors = '';
                    }

            }
            return response()->json(['success' => true, 'message' => $message]);
        }

    }
    public function update_location(Request $request)
    {
        $user = User::where(['firebase_user_key'=>$request->user_key])->get();
        if($user->count() > 0){
            $location = new UserLocations();
            $location->user_id = $user->first()->id;
            $location->lattitude = $request->latitude;
            $location->longitude = $request->longitude;
            $location->save();
        }
    }
}
