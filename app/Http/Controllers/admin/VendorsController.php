<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\VendorModel;
use App\Models\VendorDetailsModel;
use App\Models\BankdataModel;
use App\Models\CountryModel;
use App\Models\IndustryTypes;
use App\Models\States;
use App\Models\Cities;
use App\Models\BankModel;
use App\Models\BankCodetypes;
use App\Models\StoreType;
use App\Models\VendorServiceTimings;
use App\Models\VendorHolidayDates;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;
use Hash;

class VendorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!check_permission('vendor','View')) {
            abort(404);
        }
        $page_heading = "Technician";
        $datamain = VendorModel::select('*','users.name as name','users.created_at as u_created_at','industry_types.name as industry','users.active as active','users.id as id')
        ->where(['role'=>'3','users.deleted'=>'0'])
        //->with('vendordata')
        ->leftjoin('vendor_details','vendor_details.user_id','=','users.id')
        ->leftjoin('industry_types','industry_types.id','=','vendor_details.industry_type')
        ->orderBy('users.id','desc')->get();


        return view('admin.vendor.list', compact('page_heading', 'datamain'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!check_permission('vendor','Create')) {
            abort(404);
        }
        $page_heading = "Technician";
        $mode = "create";
        $id = "";
        $prefix = "";
        $name = "";
        $dial_code = "";
        $image = "";
        $active = "1";
        $states = [];
        $cities = [];
        $countries  = CountryModel::orderBy('name','asc')->get();
        $industry   = IndustryTypes::where(['deleted' => 0])->get();
        $storetype  = StoreType::where(['deleted' => 0])->get();
        $banks      = BankModel::get();
        $banks_codes = BankCodetypes::get();
        return view("admin.vendor.create", compact('page_heading', 'industry', 'id', 'name', 'dial_code', 'active','prefix','countries','states','cities','banks','banks_codes','storetype'));
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
        if(!empty($request->password))
        {
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
            $check_exist = VendorModel::where('email' ,$request->email)->where('id', '!=', $request->id)->get()->toArray();
            if (empty($check_exist)) {
                $check_exist_phone = VendorModel::where('phone', $request->phone)->where('id', '!=', $request->id)->get()->toArray();
                if(empty($check_exist_phone))
                {

                    $ins = [
                    'country_id' => $request->country_id,
                    'name'       => $request->name,
                    'email'      => $request->email,
                    'dial_code'  => $request->dial_code,
                    'phone'      => $request->phone,
                    'role'       => '3',//vendor
                    'first_name' => '',
                    'last_name'  => '',
                    'state_id'   => $request->state_id,
                    'city_id'    => $request->city_id,
                    'phone_verified'    => 1,
                    'email_verified'    => 1,
                ];



                if($request->password){
                        $ins['password'] = bcrypt($request->password);
                }

                // if($request->file("image")){
                //     $response = image_upload($request,'company','image');
                //     if($response['status']){
                //         $ins['user_image'] = $response['link'];
                //     }
                // }

                if ($file = $request->file("image")) {
                    $dir = config('global.upload_path') . "/" . config('global.user_image_upload_dir');
                    $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
                    $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
                    $ins['user_image'] = $file_name;
                }



                if ($request->id != "") {
                    $ins['updated_at'] = gmdate('Y-m-d H:i:s');
                    $user = VendorModel::find($request->id);
                    $user->update($ins);







                    $vendordata = VendorDetailsModel::where('user_id',$request->id)->first();
                    $bank       = BankdataModel::where('user_id',$request->id)->first();
                    if(empty($vendordata->id))
                    {
                    $vendordatils = new VendorDetailsModel();
                    $vendordatils->user_id = $request->id;
                    }
                    else
                    {
                     $vendordatils = VendorDetailsModel::find($vendordata->id);
                    }

                    if(empty($bank->id))
                    {
                    $bankdata = new BankdataModel();
                    $bankdata->user_id = $request->id;
                    }
                    else
                    {
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
                    $message = "Technician added successfully";
                }

                 $vendordatils->industry_type = $request->industrytype ?? '';
                 $vendordatils->homedelivery  = $request->has_own_delivery ?? '';
                 $vendordatils->branches      = $request->no_of_branches ?? '';
                 $vendordatils->company_name       = $request->company_legal_name ?? '';
                 $vendordatils->company_brand      = $request->company_brand_name ?? '';
                 $vendordatils->reg_date           = $request->business_registration_date ?? '';
                 $vendordatils->trade_license           = $request->trade_licene_number ?? '';
                 $vendordatils->trade_license_expiry    = $request->trade_licene_expiry ?? '';
                 $vendordatils->vat_reg_number      = $request->vat_registration_number ?? '';
                 $vendordatils->vat_reg_expiry      = $request->vat_expiry_date ?? '';
                 $vendordatils->store_type    = $request->store_type;
                 $vendordatils->address1      = $request->address1 ?? '';
                 $vendordatils->address2      = $request->address2 ?? '';
                 $vendordatils->street        = $request->street ?? '';
                 $vendordatils->state         = $request->state_id ?? 0;
                 $vendordatils->city          = $request->city_id ?? 0;
                 $vendordatils->zip           = $request->zip ?? '';
                 $vendordatils->identy1_type           = $request->identity_file_name_1 ?? 1;
                 $vendordatils->identy2_type           = 1;//$request->identity_file_name_2;
                 $vendordatils->company_identy1_type           = $request->company_identity_value ?? 1;
                 $vendordatils->company_identy2_type           = 1;//$request->residential_proff_value;
                 $vendordatils->deliverydays      = $request->deliverydays ?? 0;

                 // $vendordatils->holiday_dates           = $request->holiday_dates;
                    $holiday_dates = explode(',', $request->holiday_dates);


                 //logo
                 if($request->file("logo")){
                    $response = image_upload($request,'company','logo');
                    if($response['status']){
                 $vendordatils->logo = $response['link'];
                    }
                 }
                 //logo end


                  if($request->file("trade_licence")){
                    $response = image_upload($request,'company','trade_licence');
                    if($response['status']){
                 $vendordatils->trade_license_doc     = $response['link'];
                    }
                 }

                 if($request->file("chamber_of_commerce")){
                    $response = image_upload($request,'company','chamber_of_commerce');
                    if($response['status']){
                 $vendordatils->chamber_of_commerce_doc     = $response['link'];
                    }
                 }

                 if($request->file("share_certificate")){
                    $response = image_upload($request,'company','share_certificate');
                    if($response['status']){
                 $vendordatils->share_certificate_doc     = $response['link'];
                    }
                 }

                 if($request->file("power_of_attorney")){
                    $response = image_upload($request,'company','power_of_attorney');
                    if($response['status']){
                 $vendordatils->power_attorny_doc     = $response['link'];
                    }
                 }

                 if($request->file("vat_registration")){
                    $response = image_upload($request,'company','vat_registration');
                    if($response['status']){
                 $vendordatils->vat_reg_doc     = $response['link'];
                    }
                 }

                 if($request->file("signed_agrement")){
                    $response = image_upload($request,'company','signed_agrement');
                    if($response['status']){
                 $vendordatils->signed_agreement_doc     = $response['link'];
                    }
                 }

                 if($request->file("identity_file_value_1")){
                    $response = image_upload($request,'company','identity_file_value_1');
                    if($response['status']){
                 $vendordatils->identy1_doc     = $response['link'];
                    }
                 }

                //  if($request->file("identity_file_value_2")){
                //     $response = image_upload($request,'company','identity_file_value_2');
                //     if($response['status']){
                //  $vendordatils->identy2_doc     = $response['link'];
                //     }
                //  }

                 if($request->file("company_identity_file")){
                    $response = image_upload($request,'company','company_identity_file');
                    if($response['status']){
                 $vendordatils->company_identy1_doc     = $response['link'];
                    }
                 }

                 if($request->file("residential_proff_file")){
                    $response = image_upload($request,'company','residential_proff_file');
                    if($response['status']){
                 $vendordatils->company_identy2_doc     = $response['link'];
                    }
                 }

                 $vendordatils->save();
                 $bankdata->bank_name       = $request->bank_id ?? 0;
                 $bankdata->country         = $request->bankcountry ?? 0;
                 $bankdata->company_account = $request->company_account ?? '';
                 $bankdata->account_no      = $request->bank_account_number ?? '';
                 $bankdata->code_type       = $request->bank_code_type ?? 0;
                 $bankdata->branch_code     = $request->bank_branch_code ?? '';
                 $bankdata->branch_name     = $request->branch_name ?? '';

                 if($request->file("bank_statement")){
                    $response = image_upload($request,'company','bank_statement');
                    if($response['status']){
                 $bankdata->bank_statement_doc     = $response['link'];
                    }
                 }

                 if($request->file("credit_card_statement")){
                    $response = image_upload($request,'company','credit_card_statement');
                    if($response['status']){
                 $bankdata->credit_card_sta_doc     = $response['link'];
                    }
                 }





                 $bankdata->save();




                }
                else
                {
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
        if (!check_permission('vendor','Show')) {
            abort(404);
        }
        $page_heading = "Technician";
        $mode = "show";
        $vendor = VendorModel::find($id);
        return view('admin.vendor.show', compact('vendor','page_heading','mode'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!check_permission('vendor','Edit')) {
            abort(404);
        }
        $page_heading = "Edit Technician";
        $datamain = VendorModel::find($id);
        if ($datamain) {

            $datamain->vendordatils = VendorDetailsModel::where('user_id',$id)->first();
            $datamain->bankdetails = BankdataModel::where('user_id',$id)->first();

            $countries = CountryModel::orderBy('name','asc')->get();
            $industry = IndustryTypes::where(['deleted' => 0])->get();
            $banks      = BankModel::get();
            $banks_codes = BankCodetypes::get();
            $storetype  = StoreType::where(['deleted' => 0])->get();
            $user_image = asset($datamain->user_image);
            $states = States::where(['deleted' => 0, 'active' => 1, 'country_id' => $datamain->country_id])->orderBy('name', 'asc')->get();
            $cities = Cities::where(['deleted' => 0, 'active' => 1, 'state_id' => $datamain->state_id])->orderBy('name', 'asc')->get();
            $datamain->vet_availablity =VendorServiceTimings::where(['service_id'=>1,'vendor'=>$id])->first();
            $datamain->gr_availablity =VendorServiceTimings::where(['service_id'=>2,'vendor'=>$id])->first();

            $datamain->brdng_availablity =VendorServiceTimings::where(['service_id'=>3,'vendor'=>$id])->first();

            $datamain->dc_availablity =VendorServiceTimings::where(['service_id'=>4,'vendor'=>$id])->first();

            $datamain->dpt_availablity =VendorServiceTimings::where(['service_id'=>5,'vendor'=>$id])->first();

            $holiday_dates = VendorHolidayDates::where([['date','>=',time_to_uae(now(),'Y-m-d')],['vendor_id',$id]])->pluck('date')->toArray();
            if(count($holiday_dates) ){
                $datamain->holidays =   substr(implode(',', $holiday_dates), 0, -1);
            }
            // dd($datamain->holidays);




            return view("admin.vendor.create", compact('page_heading', 'datamain','id','countries','states','cities','user_image','industry','banks','banks_codes','storetype'));
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
        if(VendorModel::where('id', $request->id)->update(['active' => $request->status])) {
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
        $up['verified'] = $request->status;
        if($request->status==1){
            $up['phone_verified'] = 1;
            $up['email_verified'] = 1;
        }
        if (VendorModel::where('id', $request->id)->update($up)) {
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
            $message = "Technician removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        echo json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
    }
    public function rating($vendorID)
    {
        if (!check_permission('vendor','View')) {
            abort(404);
        }
        $page_heading = "Technician Ratings";
        $datamain = Rating::where('vendor_id',$vendorID)->get();
        return view('admin.vendor.ratings', compact('page_heading', 'datamain'));

    }
}
