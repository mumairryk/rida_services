<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\OrderModel;
use App\Models\MyPets;
use App\Models\User;
use App\Models\UserAdress;
use App\Models\MyPetsSharer;
use App\Models\Petsize;
use Hash;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Validator;

class UsersController extends Controller
{
    //
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    private function validateAccesToken($access_token)
    {

        $user = User::where(['user_access_token' => $access_token])->get();

        if ($user->count() == 0) {
            http_response_code(401);
            echo json_encode([
                'status' => "0",
                'message' => 'Invalid login',
                'oData' => [],
                'errors' => (object) [],
            ]);
            exit;

        } else {
            $user = $user->first();
            if ($user != null) { //$user->active == 1
                return $user->id;
            } else {
                http_response_code(401);
                echo json_encode([
                    'status' => "0",
                    'message' => 'Invalid login',
                    'oData' => [],
                    'errors' => (object) [],
                ]);
                exit;
                return response()->json([
                    'status' => "0",
                    'message' => 'Invalid login',
                    'oData' => [],
                    'errors' => (object) [],
                ], 401);
                exit;
            }
        }
    }

    public function my_profile(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];

        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {

            $user_id = $this->validateAccesToken($request->access_token);

            $data = User::where(['users.id' => $user_id])->select(['id', 'name','first_name','last_name', 'email', 'dial_code', 'phone', 'user_image', 'user_device_token', 'firebase_user_key'])->get();
            if ($data->count() > 0) {
                $user = $data->first();
                $o_data['data'] = convert_all_elements_to_string($user->toArray());
                $o_data['data']['id'] = $user->id;
                $status = "1";
                $message = "data fetched Successfully";
            } else {
                $message = "no data to show";
            }
        }

        return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
    }

    public function add_address(Request $request)
    {

        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $validator = Validator::make($request->all(), [
            'apartment' => 'required',
            'building' => 'required',
            'street' => 'required',
            'address_type' => 'required',
            'access_token' => 'required',
            'is_default' => 'required',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $user_id = $request->user_id ? $request->user_id : $this->validateAccesToken($request->access_token);

            if(count(UserAdress::get_address_list($user_id)->toArray()) == 0){
                $request->is_default = 1;
            }
            if ($request->is_default == 1) {
                $removedefault = UserAdress::where('user_id', $user_id)->update(['is_default' => 0]);
            }
            if ($request->address_id > 0) {
                $address = UserAdress::find($request->address_id);
            } else {
                $address = new UserAdress();
                $address->user_id = $user_id;
            }

            $address->location = isset($request->location) ? $request->location : '';
            $address->apartment = $request->apartment;
            $address->building = $request->building;
            $address->street = $request->street;
            $address->address_type = $request->address_type;
            $address->latitude = isset($request->latitude) ? $request->latitude : '';
            $address->longitude = isset($request->longitude) ? $request->longitude : '';
            $address->land_mark = $request->land_mark;
            $address->is_default = $request->is_default;
            $address->country_id = $request->country_id??0;
            $address->state_id = $request->state_id??0;
            $address->city_id = $request->area_id??0;
            $address->status = 1;
            $address->save();
            $status = "1";
            $message = "Address added successfully";
            $o_data['list'] = UserAdress::get_address_list($user_id)->toArray();
            $o_data = convert_all_elements_to_string($o_data);
        }
        return response()->json(['status' => $status, 'message' => $message, 'errors' => (object) $errors, 'oData' => (object) $o_data]);

    }
    public function edit_address(Request $request)
    {

        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $validator = Validator::make($request->all(), [
            'address_id' => 'required',
            'apartment' => 'required',
            'building' => 'required',
            'street' => 'required',
            'address_type' => 'required',
            'access_token' => 'required',
            'is_default' => 'required',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $user_id = $request->user_id ? $request->user_id : $this->validateAccesToken($request->access_token);
            $address = UserAdress::find($request->address_id);
            if (!$address) {
                $status = "0";
                $message = "No data found";
            } else {
                if ($request->is_default == 1) {
                    $removedefault = UserAdress::where('user_id', $user_id)->update(['is_default' => 0]);
                }
                $address = UserAdress::find($request->address_id);
                $address->location = isset($request->location) ? $request->location : '';
                $address->apartment = $request->apartment;
                $address->building = $request->building;
                $address->street = $request->street;
                $address->address_type = $request->address_type;
                $address->latitude = isset($request->latitude) ? $request->latitude : '';
                $address->longitude = isset($request->longitude) ? $request->longitude : '';
                $address->land_mark = $request->land_mark;
                $address->is_default = $request->is_default;
                $address->country_id = $request->country_id;
                $address->state_id = $request->state_id;
                $address->city_id = $request->area_id;
                $address->save();
                $status = "1";
                $message = "Address updated successfully";
                $o_data['list'] = UserAdress::get_address_list($user_id)->toArray();
                $o_data = convert_all_elements_to_string($o_data);
            }

        }
        return response()->json(['status' => $status, 'message' => $message, 'errors' => (object) $errors, 'oData' => (object) $o_data]);

    }
    public function setdefault(Request $request)
    {

        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $validator = Validator::make($request->all(), [
            'address_id' => 'required',
            'access_token' => 'required',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $user_id = $request->user_id ? $request->user_id : $this->validateAccesToken($request->access_token);
            $address = UserAdress::find($request->address_id);
            if (!$address) {
                $status = "0";
                $message = "No data found";
            } else {
                $removedefault = UserAdress::where('user_id', $user_id)->update(['is_default' => 0]);
                $address->is_default = 1;
                $address->save();
                $status = "1";
                $message = "Address set as default";
                $o_data['list'] = UserAdress::get_address_list($user_id)->toArray();
                $o_data = convert_all_elements_to_string($o_data);
            }

        }
        return response()->json(['status' => $status, 'message' => $message, 'errors' => (object) $errors, 'oData' => (object) $o_data]);

    }
    public function delete_address(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $validator = Validator::make($request->all(), [
            'address_id' => 'required',
            'access_token' => 'required',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $user_id = $request->user_id ? $request->user_id : $this->validateAccesToken($request->access_token);
            $address = UserAdress::find($request->address_id);
            
            if (!$address) {
                $status = "0";
                $message = "No data found";
            } else {
                $address->status = 0;
                $address->save();

                if ($address->is_default == 1) {
                    $removedefault = UserAdress::where('user_id', $user_id)->update(['is_default' => 0]);
                    
                    // make top first to default
                    $add = UserAdress::get_address_list($user_id)->first();
                    if($add){
                        $add->is_default = 1;
                        $add->save();
                    }
                }

                $status = "1";
                $message = "Address deleted successfully";
                $o_data['list'] = UserAdress::get_address_list($user_id)->toArray();
                $o_data = convert_all_elements_to_string($o_data);
            }
        }
        return response()->json(['status' => $status, 'message' => $message, 'errors' => (object) $errors, 'oData' => (object) $o_data]);

    }
    public function set_default_address(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $validator = Validator::make($request->all(), [
            'address_id' => 'required',
            'access_token' => 'required',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $user_id = $request->user_id ? $request->user_id : $this->validateAccesToken($request->access_token);
            $address = UserAdress::find($request->address_id);
            if (!$address) {
                $status = "0";
                $message = "No data found";
            } else {
                UserAdress::where(['user_id' => $user_id])->update(['is_default' => 0]);
                $address = UserAdress::find($request->address_id);
                $address->is_default = 1;
                $address->save();
                $status = "1";
                $message = "Address changed to default address";
                $o_data['list'] = UserAdress::get_address_list($user_id)->toArray();
                $o_data = convert_all_elements_to_string($o_data);
            }
        }
        return response()->json(['status' => $status, 'message' => $message, 'errors' => (object) $errors, 'oData' => (object) $o_data]);

    }
    public function list_address(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $user_id = $this->validateAccesToken($request->access_token);
            $status = "1";
            $message = "Address fetched successfully";
            $o_data['list'] = UserAdress::get_address_list($user_id);
            $o_data = convert_all_elements_to_string($o_data);
        }
        return response()->json(['status' => $status, 'message' => $message, 'errors' => (object) $errors, 'oData' => (object) $o_data]);

    }
    public function update_user_profile(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $user_id = $request->user_id ? $request->user_id : $this->validateAccesToken($request->access_token);
        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'user_image' => 'mimes:jpeg,png,jpg',
            // 'dial_code' => 'required',
            // 'phone' => 'required',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {

            $user = User::find($user_id);

            if ($file = $request->file("user_image")) {
                $dir = config('global.upload_path') . "/" . config('global.user_image_upload_dir');
                $file_name = time() . uniqid() . "." . $file->getClientOriginalExtension();
                $file->storeAs(config('global.user_image_upload_dir'), $file_name, config('global.upload_bucket'));
                $user->user_image = $file_name;
            }

            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->name = $request->first_name. ' ' . $request->last_name;
            if($request->phone && !$request->is_web)
            {
                $user->phone = $request->phone;
                $user->dial_code = $request->dial_code;
                if($user->phone != $request->phone){
                    $user->phone_verified = 0;
                    $user->user_phone_otp = rand(1000, 9999);
                }
            }
           
            $user->save();


            $data = User::select('id','name','first_name','last_name','email','user_image','dial_code','phone','email_verified','phone_verified','firebase_user_key')->where(['users.id' => $user_id])->get();
            $o_data = $data->first();
            if($o_data){
                $o_data->is_email_verifed = $o_data->email_verified ?? 0;
                $o_data->is_phone_verified = $o_data->phone_verified ?? 0;
                $o_data->image = $o_data->user_image;
            }
            
            $o_data = convert_all_elements_to_string($o_data);
            $status = "1";
            $message = "Profile updated Successfully";

            //enable exec on server
            if (config('global.server_mode') == 'local') {
                \Artisan::call('update:firebase_node ' . $user_id);
            } else {
                exec("php " . base_path() . "/artisan update:firebase_node " . $user_id . " > /dev/null 2>&1 & ");
            }

        }
        return response()->json(['status' => $status, 'message' => $message, 'errors' => $errors, 'oData' => $o_data]);
    }

    public function change_phone_number(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $user_id = $this->validateAccesToken($request->access_token);
        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
            'dial_code' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $user = User::find($user_id);
            if ($user->dial_code != $request->dial_code || $user->phone != $request->phone) {

                if (User::where('phone', $request->phone)->where('dial_code', $request->dial_code)->first() != null) {
                    return response()->json([
                        'status' => "0",
                        'error' => (object) array(),
                        'message' => 'This number already in use',
                    ], 201);
                }

                $mobile = $request->dial_code . $request->phone;
                $otp = generate_otp();
                $messagen = "OTP to confirm your mobile number at " . config('global.site_name') . " is " . $otp;
                $st = send_normal_SMS($messagen, $mobile);
                if ($st != 1) {
                    return response()->json([
                        'status' => "0",
                        'error' => (object) array(),
                        'message' => $st,
                    ], 201);
                }
                $status = "1";
                $message = "Please verify the otp ";
                $o_data = [
                    'dial_code' => $request->dial_code,
                    'phone' => $request->phone,
                ];
                $user->user_phone_otp = $otp;
                $user->phone_verified = 0;
                $user->save();
            } else {
                $message = "There is no change in your phone number";
            }

        }
        return response()->json(['status' => $status, 'message' => $message, 'errors' => $errors, 'oData' => $o_data]);
    }
    public function validate_otp_phone_email_update(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $user_id = $this->validateAccesToken($request->access_token);
        $rule = [
            'access_token' => 'required',
            'type' => 'required|in:1,2',
            'otp' => 'required',
        ];

        if ($request->type == 1) {
            $rule['dial_code'] = 'required';
            $rule['phone'] = 'required';
        } else {
            $rule['email'] = 'required';
        }
        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $user = User::find($user_id);
            $sent_opt = $user->user_phone_otp;
            if ($request->type == 2) {
                $sent_opt = $user->user_email_otp;
            }
            if ($sent_opt == $request->otp) {

                if ($request->type == 1) {
                    $user->dial_code = $request->dial_code;
                    $user->phone = $request->phone;
                    $user->phone_verified = 1;
                    $user->user_phone_otp = '';
                    $user->save();
                    $status = "1";
                    $message = "Phone number updated successfully";
                } else {
                    $user->email = $request->email;
                    $user->user_email_otp = '';
                    $user->save();
                    $status = "1";
                    $message = "email id updated successfully";
                }
                if (config('global.server_mode') == 'local') {
                    \Artisan::call('update:firebase_node ' . $user_id);
                } else {
                    exec("php " . base_path() . "/artisan update:firebase_node " . $user_id . " > /dev/null 2>&1 & ");
                }

            } else {
                $message = "Invalid otp sent";
            }

        }
        return response()->json(['status' => $status, 'message' => $message, 'errors' => $errors, 'oData' => $o_data]);
    }

    public function change_email(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $user_id = $this->validateAccesToken($request->access_token);
        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
            'email' => 'required|unique:users,email,' . $user_id,
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $user = User::find($user_id);
            if ($user->email != $request->email) {
                $lemail = strtolower($request->email);
                if (User::whereRaw("LOWER(email) = '$lemail'")->first() != null) {
                    return response()->json([
                        'status' => "0",
                        'error' => (object) array(),
                        'message' => 'This email already in use',
                    ], 201);
                }

                $otp = generate_otp();
                $name = $user->name;
                $mailbody = view('emai_templates.change_email_otp', compact('otp', 'name'));
                $ret = send_email($request->email, config('global.site_name') . " email change request", $mailbody);
                if ($ret) {
                    $status = "1";
                    $message = "Please verify the otp ";
                    $o_data = [
                        'email' => $request->email,
                    ];
                    $user->user_email_otp = $otp;
                    $user->save();
                } else {
                    $message = "Faild to sent mail. please try again after some times";
                }
            } else {
                $message = "There is no change in your phone number";
            }

        }
        return response()->json(['status' => $status, 'message' => $message, 'errors' => $errors, 'oData' => $o_data]);
    }
    public function change_password(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $user_id = $this->validateAccesToken($request->access_token);
        // $validator = Validator::make($request->all(), [
        //     'access_token' => 'required',
        //     'old_password' => 'required',
        //     'new_password' => 'required|confirmed',
        //     'password_confirmation' => 'required',
        // ]);

        $rules = [
            'access_token' => 'required',
            'old_password' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ];
        $messages = [
            'old_password.required' => trans('validation.password_required'),
            'password.required' => trans('validation.password_required'),
            'password.confirmed' => trans('validation.password_confirmed'),
            'password_confirmation.required' => trans('validation.password_confirmation_required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);


        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $user = User::find($user_id);
            if (Hash::check($request->old_password, $user->password)) {
                $user->password = bcrypt($request->password);
                $user->save();
                $status = "1";
                $message = "Password Updated successfully";
            } else {
                $message = "Old password not match";
            }

        }
        return response()->json(['status' => $status, 'message' => $message, 'errors' => (object) $errors, 'oData' => (object) $o_data]);
    }

    public function my_pets(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];

        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $user_id = $this->validateAccesToken($request->access_token);


            $data = MyPets::select('id','name','user_id','breed_id','sex','dob','weight','food','additional_notes','active','species as species_id','image','medicine_instructions','size')->with(['pets_sharers.user','breed'=>function($q){
                $q->select('breeds.id', 'breeds.name','room_type_id as cage_type_id','room_types.name as cage_type','appoint_time_id','appointment_times.name as appointment_time','minutes_required as appointment_time_in_minutes')->leftjoin('room_types','room_types.id','room_type_id')->leftjoin('appointment_times','appointment_times.id','appoint_time_id');
            }])->where(['user_id'=>$user_id,'deleted'=>0])->orderBy('id','desc')
            ->orWhereHas('pets_sharers', function($Query) use($user_id) {
                return $Query->where('user_id', $user_id)
                ->whereHas('pet', function($Query) use($user_id) {
                    return $Query->where(['deleted'=>0]);
                });
            })

            ->get();
            if ($data->count() > 0) {
                foreach ($data as $key => $row) {
                    if($row->deleted){
                        continue;
                    }
                    $share_users = [];
                    foreach ($row->pets_sharers as $key => $ro) {
                        if(isset($ro->user)){
                            $share_users[] = ['id'=>$ro->user->id,'name'=>$ro->user->name];
                        }
                    }
                    unset($row->pets_sharers);
                    $row->share_users = $share_users;
                }
                $o_data = convert_all_elements_to_string($data);
                $status = "1";
                $message = "data fetched Successfully";
            } else {
                $message = "no data to show";
            }
        }
        return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' =>  $o_data], 200);
    }

    public function add_pet(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];


        $rules = [
            'access_token' => 'required',
            'name' => 'required',
            'species' => 'required',
            'breed_id' => 'required',
            'dob' => 'required',
            'weight' => 'required|numeric|min:0|not_in:0',
            'sex' => 'required',
            // 'food' => 'required',
               
        ];
        $messages = [
            'name.required' => "Name required",
            'species.required' => "Species required",
            'breed_id.required' => "Breed required",
            'dob.required' => "DOB required",
            'weight.required' => "Weight required",
            'weight.numeric' => "Invalid weight",
            'weight.min' => "Invalid weight",
            'weight.not_in' => "Invalid weight",
            'sex.required' => "Sex required",
            // 'food.required' => "Food required",
            'image.mimes' => "Invalid image",
        ];
        if(isset($request->image) && $request->image){
            $rules['image'] = 'mimes:jpeg,png,jpg';
        }
        $validator = Validator::make($request->all(), $rules, $messages);


        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $user_id = $request->user_id ? $request->user_id : $this->validateAccesToken($request->access_token);

            $ins = [
                'user_id' => $user_id,
                'name' => $request->name,
                'species' => $request->species,
                'breed_id' => $request->breed_id,
                'sex' => $request->sex,
                'dob' => date('Y-m-d', strtotime($request->dob)),
                'weight' => $request->weight,
                'food' => $request->food??'',
                'additional_notes' => $request->additional_notes ?? '',
                'updated_at' => gmdate('Y-m-d H:i:s'),
                'created_at' => gmdate('Y-m-d H:i:s'),
                'size' => $request->size ?? 2,
                'active' => 1,
                'medicine_instructions' => $request->medicine_instructions ?? '',
            ];
            if ($file = $request->file("image")) {
                $file_name = time() . uniqid() . "_img." . $file->getClientOriginalExtension();
                $file->storeAs(config('global.pet_image_upload_dir'), $file_name, config('global.upload_bucket'));
                $ins['image'] = $file_name;
            }
            if (MyPets::create($ins)) {
                $status = "1";
                $message = "Successfully created";
            } else {
                $message = "Something went wrong";
            }
        }
        return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
    }
    public function update_pet(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];


        $rules = [
            'access_token' => 'required',
            'id' => 'required',
            'name' => 'required',
            'species' => 'required',
            'breed_id' => 'required',
            //'size' => 'required',
            'dob' => 'required',
            'weight' => 'required|numeric|min:0|not_in:0',
            'sex' => 'required',
            // 'food' => 'required',
               
        ];
        $messages = [
            'id.required' => "Pet Id is required",
            'name.required' => "Name required",
            'species.required' => "Species required",
            'breed_id.required' => "Breed required",
            //'size.required' => "Size required",
            'dob.required' => "DOB required",
            'weight.required' => "Weight required",
            'weight.numeric' => "Invalid weight",
            'weight.min' => "Invalid weight",
            'weight.not_in' => "Invalid weight",
            'sex.required' => "Sex required",
            // 'food.required' => "Food required",
            'image.mimes' => "Invalid image",
        ];
        if(isset($request->image) && $request->image){
            $rules['image'] = 'mimes:jpeg,png,jpg';
        }
        $validator = Validator::make($request->all(), $rules, $messages);


        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $user_id = $request->user_id ? $request->user_id : $this->validateAccesToken($request->access_token);
            $pet = MyPets::where(['id'=>$request->id,'user_id'=>$user_id,'deleted'=>0])->first();
            if(!$pet){
                $message = "Pet not found.";
                return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);

            }
            $ins = [
                'user_id' => $user_id,
                'name' => $request->name,
                'species' => $request->species,
                'breed_id' => $request->breed_id,
                'sex' => $request->sex,
                'dob' => date('Y-m-d', strtotime($request->dob)),
                'weight' => $request->weight,
                'food' => $request->food ?? '',
                'additional_notes' => $request->additional_notes ?? '',
                'updated_at' => gmdate('Y-m-d H:i:s'),
                'created_at' => gmdate('Y-m-d H:i:s'),
                'active' => 1,
                'size' => $request->size ?? 2,
                'medicine_instructions' => $request->medicine_instructions ?? '',
            ];
            if ($file = $request->file("image")) {
                $file_name = time() . uniqid() . "_img." . $file->getClientOriginalExtension();
                $file->storeAs(config('global.pet_image_upload_dir'), $file_name, config('global.upload_bucket'));
                $ins['image'] = $file_name;
            }
            if ($pet->update($ins)) {
                $status = "1";
                $message = "Successfully updated";
            } else {
                $message = "Something went wrong";
            }
        }
        return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
    }
    public function get_pet(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];


        $rules = [
            'access_token' => 'required',
            'id' => 'required',
               
        ];
        $messages = [
            'id.required' => "Pet Id is required",
        ];
        if(isset($request->image) && $request->image){
            $rules['image'] = 'mimes:jpeg,png,jpg';
        }
        $validator = Validator::make($request->all(), $rules, $messages);


        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $user_id = $this->validateAccesToken($request->access_token);
            // $pet = MyPets::where(['id'=>$request->id,'user_id'=>$user_id,'deleted'=>0])->first();

            $pet = MyPets::
            // select('id','name','breed_id','sex','dob','weight','food','additional_notes','active','species as species_id','image','medicine_instructions')->
            with(['sps','breed'=>function($q){
                $q->select('breeds.id', 'breeds.name','room_type_id as cage_type_id','room_types.name as cage_type','appoint_time_id','appointment_times.name as appointment_time','minutes_required as appointment_time_in_minutes')
                ->leftjoin('room_types','room_types.id','room_type_id')
                ->leftjoin('appointment_times','appointment_times.id','appoint_time_id');
            },'size_det'=>function($q){
                $q->select('id','name');
            }])->where(['id'=>$request->id,'user_id'=>$user_id,'deleted'=>0])->orderBy('id','desc')->first();
            if(!$pet){
                $message = "Pet not found.";
                return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);

            }
            if($pet->size && $pet->size_det!=null) {
                $pet->size_text = $pet->size_det->name;
                unset($pet->size_det);
            } else {
                $pet->size_text = "";
            }
            $o_data = convert_all_elements_to_string([$pet])[0] ?? '';
            $status = "1";
            $message = "Successfully updated";
        }
        return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
    }

    public function pet_size(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];

        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $user_id = $this->validateAccesToken($request->access_token);


            $data = Petsize::select('id','name')->where(['pet_sizes.deleted' => 0])->orderBy('id','asc')->get();
            
            if ($data->count() > 0) {
               
                $o_data = convert_all_elements_to_string($data);
                $status = "1";
                $message = "data fetched Successfully";
            } else {
                $message = "no data to show";
            }
        }
        return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' =>  $o_data], 200);
    }

    public function share_pet(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];


        $rules = [
            'access_token' => 'required',
            'id' => 'required',
            'email' => 'required',
               
        ];
        $messages = [
            'id.required' => "Pet Id is required",
            'email.required' => "Email is required",
        ];
        if(isset($request->image) && $request->image){
            $rules['image'] = 'mimes:jpeg,png,jpg';
        }
        $validator = Validator::make($request->all(), $rules, $messages);


        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $user_id = $request->user_id ? $request->user_id : $this->validateAccesToken($request->access_token);
            // $pet = MyPets::where(['id'=>$request->id,'user_id'=>$user_id,'deleted'=>0])->first();

            $pet = MyPets::
            // select('id','name','breed_id','sex','dob','weight','food','additional_notes','active','species as species_id','image','medicine_instructions')->
            with(['sps','breed'=>function($q){
                $q->select('breeds.id', 'breeds.name','room_type_id as cage_type_id','room_types.name as cage_type','appoint_time_id','appointment_times.name as appointment_time','minutes_required as appointment_time_in_minutes')
                ->leftjoin('room_types','room_types.id','room_type_id')
                ->leftjoin('appointment_times','appointment_times.id','appoint_time_id');
            }])->where(['id'=>$request->id,'user_id'=>$user_id,'deleted'=>0])->orderBy('id','desc')->first();
            if(!$pet){
                $message = "Pet not found.";
                return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
            }

            $lemail = strtolower($request->email);
            $user = User::whereRaw("LOWER(email) = '$lemail'")->where('deleted', 0)->where(['role' => 2])->first();
            if(!$user){
                $message = "Pet not found.";
                return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
            }
            $my_pets_sh = MyPetsSharer::where([['user_id' , $user->id],['pet_id' , $pet->id]])->first();
            if(!$my_pets_sh){
                $my_pets_sh = new MyPetsSharer();
            }
            $my_pets_sh->user_id =  $user->id;
            $my_pets_sh->pet_id =  $pet->id;
            
            $my_pets_sh->save();
            // $o_data = convert_all_elements_to_string([$pet])[0] ?? '';
            $status = "1";
            $message = "Pet has been shared Successfully";
        }
        return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
    }

    public function delete_pet(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'access_token' => 'required',
        ]);

        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $user_id = $request->user_id ? $request->user_id : $this->validateAccesToken($request->access_token);
            
            $pet = MyPets::where(['id'=>$request->id,'user_id'=>$user_id,'deleted'=>0])->first();
            if(!$pet){
                $message = "Pet not found.";
            } else {
                $pet->deleted = 1;
                $pet->save();
                $status = "1";
                $message = "Pet has been deleted successfully";
                // return $this->my_pets($request);
            }
        }
        return response()->json(['status' => $status, 'message' => $message, 'errors' => (object) $errors, 'oData' => (object) $o_data]);

    }
    public function delete_pet_share(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];


        $rules = [
            'access_token' => 'required',
            'id' => 'required',
               
        ];
        $messages = [
            'id.required' => "Pet Id is required",
        ];
        if(isset($request->image) && $request->image){
            $rules['image'] = 'mimes:jpeg,png,jpg';
        }
        $validator = Validator::make($request->all(), $rules, $messages);


        if ($validator->fails()) {
            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {
            $user_id = $request->user_id ? $request->user_id : $this->validateAccesToken($request->access_token);
            // $pet = MyPets::where(['id'=>$request->id,'user_id'=>$user_id,'deleted'=>0])->first();

            $pet = MyPets::
            with(['sps','breed'=>function($q){
                $q->select('breeds.id', 'breeds.name','room_type_id as cage_type_id','room_types.name as cage_type','appoint_time_id','appointment_times.name as appointment_time','minutes_required as appointment_time_in_minutes')
                ->leftjoin('room_types','room_types.id','room_type_id')
                ->leftjoin('appointment_times','appointment_times.id','appoint_time_id');
            }])->where(['id'=>$request->id,'deleted'=>0])->orderBy('id','desc')->first();
            if(!$pet){
                $message = "Pet not found.";
                return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
            }
            $user = User::where("id",$user_id)->where('deleted', 0)->where(['role' => 2])->first();
            if(!$user){
                $message = "Pet not found.";
                return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
            }
            $my_pets_sh = MyPetsSharer::where([['user_id' , $user->id],['pet_id' , $pet->id]])->first();
            if(!$my_pets_sh){
                $message = "Pet not found.";
                return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
            }

            $my_pets_sh->delete();
            // $o_data = convert_all_elements_to_string([$pet])[0] ?? '';
            $status = "1";
            $message = "Pet has been deleted Successfully";
        }
        return response()->json(['status' => $status, 'error' => (object) $errors, 'message' => $message, 'oData' => (object) $o_data], 200);
    }

}
