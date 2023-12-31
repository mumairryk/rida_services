<?php
namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TempUser;
use Carbon\Carbon;
// use Kreait\Firebase\Database;
use Hash;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Validator;

class AuthController extends Controller
{
    public $lang = '';
    public function __construct(Database $database, Request $request)
    {
        $this->database = $database;
        if (isset($request->lang)) {
            \App::setLocale($request->lang);
        }
        $this->lang = \App::getLocale();
    }
     public function signup(Request $request)
    {
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'dial_code' => 'required',
            'phone' => 'required',
            'device_type' => 'required',
            'fcm_token' => 'required',
            'password' => 'required',
            'conf_password' => 'required',
        ];
        $messages = [
            'email.required' => trans('validation.email_required'),
            'email.email' => trans('validation.valid_email'),
            'first_name.required' => trans('validation.name_required'),
            'last_name.required' => trans('validation.name_required'),
            'password.required' => trans('validation.password_required'),
            'fcm_token.required' => trans('validation.fcm_token_required'),
            'device_type.required' => trans('validation.device_type_required'),
            'phone.required' => trans('validation.mobile_required'),
            'dial_code.required' => trans('validation.dial_code_required'),
            'conf_password.required' => "Confirm password is required",
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $status = 0;
            $message = trans('validation.validation_error_occured');
            $errors = $validator->messages();

            return response()->json([
                'status' => "0",
                'message' => $message,
                'error' => (object) $errors,
                'o_data' => (object)[],
            ], 200);
        }

        $lemail = strtolower($request->email);
        User::whereRaw("LOWER(email) = '$lemail'")->where('email_verified', 0)->where('phone_verified', 0)->delete();
        User::where('phone', $request->phone)->where('dial_code', $request->dial_code)->where('email_verified', 0)->where('phone_verified', 0)->delete();

        if ($request->password != $request->conf_password) {
            return response()->json([
                'status' => "0",
                'error' => (object) array(),
                'message' => "Passwords are mismatched",
                'o_data' => (object)[],
            ], 200);
        }


        if (User::where('email', $request->email)->first() != null) {
            return response()->json([
                'status' => "0",
                'error' => (object) array(),
                'message' => trans('validation.email_already_registered_please_login'),
                'o_data' => (object)[],
            ], 200);
        }
        if (User::where('phone', $request->phone)->where('dial_code', $request->dial_code)->first() != null) {
            return response()->json([
                'status' => "0",
                'error' => (object) array(),
                'message' => trans('validation.phone_already_registered_please_login'),
                'o_data' => (object)[],
            ], 200);
        }

        $TempUser = TempUser::where('email', $request->email)->first();
        if(!$TempUser){
            $TempUser = TempUser::where('phone', $request->phone)->where('dial_code', $request->dial_code)->first();
        }
        $TempUser = $TempUser ? $TempUser : new TempUser();

        $TempUser->fill([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'dial_code' => $request->dial_code,
            'email_verified_at' => Carbon::now(),
            'email_verified' => 1,
            'user_device_type' => $request->device_type,
            'user_device_token' => $request->fcm_token,
            'device_cart_id' => $request->device_cart_id,
            'password' => bcrypt($request->password),
            'user_phone_otp' => (string)get_otp(),
            'user_email_otp' => (string)get_otp(),
            'role' => 2,
            'phone_verified' => 0,
            'active' => 1,
        ]);

        $TempUser->save();

        

        $otp = $TempUser->user_email_otp;
        $name = $TempUser->first_name . ' ' . $TempUser->last_name;      

        if (config('global.server_mode') == 'local') {
            \Artisan::call("send:send_verification_email --uri=" . urlencode("Verify your email") . " --uri2=" . urlencode($TempUser->email) . " --uri3=" . $otp . " --uri4=" . urlencode($name));
        } else {
            exec("php " . base_path() . "/artisan send:send_verification_email --uri=" . urlencode("Verify your email") . " --uri2=" . urlencode($TempUser->email) . " --uri3=" . $otp . " --uri4=" . urlencode($name) . " > /dev/null 2>&1 & ");
        }

        return response()->json([
            'status' => "1",           
            'message' => "Registration Successful please verify your Mobile",
            'o_data' => $TempUser,
            'error'=>(object)[]
            
        ], 201);
    }
    public function confirm_phone_code(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'otp' => 'required',
        ];
        $messages = [
            'user_id.required' => trans('validation.user_id_required'),
            'otp.required' => trans('validation.otp_required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $message = trans('validation.validation_error_occured');
            $errors = $validator->messages();
            return response()->json([
                'status' => "0",
                'message' => $message,
                'error' => (object) $errors,
            ], 200);
        }

        // $TempUser = User::where('id', $request->user_id)->first();
        $TempUser = TempUser::where('id', $request->user_id)->first();
        if (!($TempUser)) {
            $message = trans('validation.invalid_user');
            return response()->json([
                'status' => "0",
                'message' => $message,
                'error' => (object) array(),
            ], 401);
        }
        if (($TempUser->user_phone_otp == $request->otp)) {

            $user = User::where('email', $TempUser->email)->where('phone', $TempUser->phone)->first();
            $user = $user ? $user : new User();

            $user->fill([
                'first_name' => $TempUser->first_name,
                'last_name' => $TempUser->last_name,
                'name' => $TempUser->first_name . ' ' . $TempUser->last_name,
                'email' => $TempUser->email,
                'phone' => $TempUser->phone,
                'dial_code' => $TempUser->dial_code,
                'email_verified_at' => Carbon::now(),
                'email_verified' => 1,
                'user_device_type' => $TempUser->device_type,
                'user_device_token' => $TempUser->fcm_token,
                'password' => $TempUser->password,
                'user_phone_otp' => '',
                'user_email_otp' => '',
                'role' => 2,
                'phone_verified' => 1,
                'active' => 1,
            ]);

            $user->save();

            if($TempUser->device_cart_id){
                \App\Models\Cart::where('device_cart_id',$TempUser->device_cart_id)->update(['user_id'=>$user->id]);
            }

            
            $tokenResult = $user->createToken('Personal Access Token')->accessToken;
            $token = $tokenResult->token;
            $tokenResult->expires_at = Carbon::now()->addWeeks(100);        
            $user->user_access_token = $token;        
            $user->save();

            if ($user->firebase_user_key == null) {
                $fb_user_refrence = $this->database->getReference('Users/')
                    ->push([
                        'fcm_token' => $user->fcm_token,
                        'name' => $user->name,
                        'email' => $user->email,
                        'active' => 1,
                        'user_id' => $user->id,
                        'user_image' => $user->user_image,
                    ]);
                $user->firebase_user_key = $fb_user_refrence->getKey();
            }else{
                $this->database->getReference('Users/' . $user->firebase_user_key . '/')->update(['fcm_token' => $user->fcm_token,'active' => 1,'user_image' => $user->user_image]);
            }

            $user->save();

            $uname = $user->name ?? $user->first_name . ' ' . $user->last_name;
            $umail = $user->email;
            
            if (config('global.server_mode') == 'local') {
                \Artisan::call("send:send_reg_email --uri=" . urlencode("Welcome to The HOP") . " --uri2=" . urlencode($umail) . " --uri3=" . urlencode($uname));
            } else {
                exec("php " . base_path() . "/artisan send:send_reg_email --uri=" . urlencode("Welcome to The HOP") . " --uri2=" . urlencode($umail) . " --uri3=" . urlencode($uname) . " > /dev/null 2>&1 & ");
            }


            $tokenResult = $user->createToken('Personal Access Token')->accessToken;
            return $this->loginSuccess($tokenResult, $user);

            return response()->json([
                'status' => "1",
                'message' => trans('validation.phone_verified_successfully'),
                'access_token' => $token,
                'firebase_user_key' => $user->firebase_user_key,
            ], 200);
        } else {
            return response()->json([
                'status' => "0",
                'error' => (object) array(),
                'message' => trans('validation.code_does_not_match_you_can_request_for_resending_the_code'),
            ], 200);
        }
    }
    protected function loginSuccess($tokenResult, $user, $msg = '')
    {
        $token = $tokenResult->token;
        $tokenResult->expires_at = Carbon::now()->addWeeks(100);
        $users = [];
        if (!empty($user)) {

            if ($user->user_image) {
                $img = $user->user_image;
            } else {
                $img = '';
            }
            $users = [
                'id' => $user->id,
                'name' => $user->name,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'is_social' => $user->is_social,
                'image' => $img,
                'dial_code' => $user->dial_code ? $user->dial_code : '',
                'phone' => isset($user->phone) ? $user->phone : '',
                'is_email_verifed' => $user->email_verified ?? 0,
                'is_phone_verified' => $user->phone_verified ?? 0,
            ];
        }

        $user->user_access_token = $token;
        $user->save();

        if ($user->firebase_user_key == null) {
            $fb_user_refrence = $this->database->getReference('Users/')
                ->push([
                    'fcm_token' => $user->user_device_token,
                    'name' => $user->name,
                    'email' => $user->email,
                    'user_id' => $user->id,
                    'active' => 1,
                    'user_image' => $user->user_image,
                ]);
            $user->firebase_user_key = $fb_user_refrence->getKey();
        } else {
            $this->database->getReference('Users/' . $user->firebase_user_key . '/')->update(['fcm_token' => $user->fcm_token,'active' => 1,'user_image' => $user->user_image]);

            // $this->database->getReference('Users/' . $user->firebase_user_key . '/')->update(['fcm_token' => $user->user_device_token]);
        }

        $user->save();
        $users['firebase_user_key'] = $user->firebase_user_key;

        if (config('global.server_mode') == 'local') {
            \Artisan::call('update:firebase_node ' . $user->id);
        } else {
            exec("php " . base_path() . "/artisan update:firebase_node " . $user->id . " > /dev/null 2>&1 & ");
        }
        return response()->json([
            'status' => "1",
            'message' => $msg ? $msg : trans('validation.successfully_logged_in'),
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenResult->expires_at)->toDateTimeString(),
            'firebase_user_key' => $user->firebase_user_key,
            'user' => convert_all_elements_to_string($users),
        ]);
    }
    public function email_login(Request $request)
    {
        $rules = [
            'password' => 'required',
            'email' => 'required|email',
            'device_type' => 'required',
            'fcm_token' => 'required',
            // _if:device_type,!=,0
        ];
        $messages = [
            'password.required' => trans('validation.password_required'),
            'email.required' => trans('validation.email_required'),
            'email.email' => trans('validation.valid_email'),
            'fcm_token.required_if' => trans('validation.fcm_token_required'),
            'device_type.required' => trans('validation.device_type_required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $status = 0;
            $message = trans('validation.validation_error_occured');
            $errors = $validator->messages();
            return response()->json([
                'status' => "0",
                'message' => $message,
                'error' => (object) $errors,
            ], 200);
        }
        $lemail = strtolower($request->email);
        $user = User::whereRaw("LOWER(email) = '$lemail'")->where('deleted', 0)->where(['role' => 2])->first();
        if ($user != null) {
            if (Hash::check($request->password, $user->password)) {
                $msg = '';
                if (!$user->active) {
                    return response()->json(['status' => "0", 'error' => (object) array(), 'message' => trans('validation.account_deactivated_please_contact_admin_for_more_information'), 'user' => null], 200);
                }
                // if (!$user->email_verified) {
                //     return response()->json(['status' => "0", 'error' => (object) array(), 'message' => trans('validation.email_not_verified'), 'user' => $user, 'is_email_verifed' => 0], 200);
                // }
                if(!$request->is_web){
                    if (!$user->phone_verified) {
                        return response()->json(['status' => 0, 'message' => trans('validation.mobile_not_verified'), 'user' => $user, 'is_mobile_verifed' => 0], 200);
                    }
                }

                $user->user_device_token = $request->fcm_token;
                $user->save();

                $tokenResult = $user->createToken('Personal Access Token')->accessToken;
                if(isset($request->device_cart_id) && $request->device_cart_id){
                    \App\Models\Cart::where('device_cart_id',$request->device_cart_id)->update(['user_id'=>$user->id]);
                }
                $user->is_social = 0;

                return $this->loginSuccess($tokenResult, $user, $msg);
            } else {
                return response()->json(['status' => "0", 'error' => (object) array(), 'message' => trans('validation.invalid_credentials'), 'user' => null], 200);
            }
        } else {
            return response()->json(['status' => "0", 'error' => (object) array(), 'message' => trans('validation.user_not_found'), 'user' => null], 200);
        }
    }
    public function logout(Request $request)
    {
        $rules = [
            'access_token' => 'required',
        ];
        $messages = [
            'access_token.required' => trans('validation.access_token_required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $message = trans('validation.validation_error_occured');
            $errors = $validator->messages();
            return response()->json([
                'status' => "0",
                'message' => $message,
                'error' => (object) $errors,
            ], 200);
        }
        $user = User::where(['user_access_token' => $request->access_token])->first();
        if (!$user) {
            http_response_code(401);
            echo json_encode([
                'status' => "0",
                'message' => trans('validation.invalid_access_token'),
                'oData' => [],
                'errors' => (object) [],
            ]);
            exit;
        } else {
            $user->user_device_token = '';
            $user->save();
            return response()->json(['status' => "1",
                'message' => trans('validation.successfully_logged_out'),
                'oData' => [],
                'errors' => (object) []], 200);
        }
    }

    public function delete_account(Request $request)
    {
        // $validator = Validator::make(
        //     $request->all(),
        //     [
        //         'access_token' => 'required',
        //     ]
        // );
        $rules = [
            'access_token' => 'required',
        ];
        $messages = [
            'access_token.required' => trans('validation.access_token_required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $message = trans('validation.validation_error_occured');
            $errors = $validator->messages();
            return response()->json([
                'status' => "0",
                'message' => $message,
                'error' => (object) $errors,
            ], 200);
        }
        $user = User::where(['user_access_token' => $request->access_token])->where('role',2)->first();
        if (!$user) {
            http_response_code(401);
            echo json_encode([
                'status' => "0",
                'message' => trans('validation.invalid_access_token'),
                'oData' => [],
                'errors' => (object) [],
            ]);
            exit;
        } else {

            $fb_user_refrence = $this->database->getReference('users_locations/' . $user->firebase_user_key . '/')->remove();

            $user->user_device_token = '';
            $user->email = $user->email . "__deleted_account" . $user->id;
            $user->phone = $user->phone . "__deleted_account" . $user->id;
            $user->deleted = 1;
            $user->user_access_token = '';
            $user->save();
            return response()->json(['status' => "1",
                'message' => trans('validation.successfully_deleted_your_account'),
                'oData' => [],
                'errors' => (object) []], 200);
        }
    }
    public function resend_phone_code(Request $request)
    {

        $TempUser = TempUser::where('id', $request->user_id)->first();

        $otp = (string)get_otp();
        $TempUser->user_phone_otp = $otp;

        $mobile = $TempUser->dial_code . $TempUser->phone;
        $messagen = "OTP to confirm HOP registration is " . $otp;
        $st = 1;//send_normal_SMS($messagen, $mobile);
        if ($st != 1) {
            return response()->json([
                'status' => "0",
                'error' => (object) array(),
                'message' => $st,
            ], 201);
        }
        $TempUser->save();

        return response()->json([
            'status' => "1",
            'message' => trans('validation.verification_code_is_sent_again'),
            'user' => $otp,
        ], 200);
    }
    public function social_login(Request $request)
    {

        $rules = [
            'email' => 'required|email',
            'first_name' => 'required',
            'device_type' => 'required',
            'fcm_token' => 'required',
        ];
        $messages = [
            'email.required' => trans('validation.email_required'),
            'email.email' => trans('validation.valid_email'),
            'first_name.required' => trans('validation.name_required'),
            'fcm_token.required' => trans('validation.fcm_token_required'),
            'device_type.required' => trans('validation.device_type_required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $message = trans('validation.validation_error_occured');
            $errors = $validator->messages();
            return response()->json([
                'status' => "0",
                'message' => $message,
                'error' => (object) $errors,
            ], 200);
        }
        if ($user = User::where('email', $request->email)->where("deleted", 0)->where(function ($query) {
            $query->where('role', 2)
            ->orWhereNull('role');
        })->first()) {
            User::where('id', '!=', $user->id)->where('email', $request->email)->where("deleted", 0)->where(function ($query) {
                $query->where('role', 2)
                    ->orWhereNull('role');
            })->delete();
            // $user = User::where('email', $request->email)->first();
            $user->user_device_token = $request->fcm_token;
            $user->email_verified = 1;
            $user->role = 2;
            $user->active = 1;
            $user->is_social = 1;
            $user->save();
            if(isset($request->device_cart_id) && $request->device_cart_id){
                \App\Models\Cart::where('device_cart_id',$request->device_cart_id)->update(['user_id'=>$user->id]);
            }

        } else {
            $user = new User([
                'first_name' => $request->first_name,
                'last_name' => '',
                'name' => $request->first_name,
                'email' => $request->email,
                'user_device_type' => $request->device_type,
                'user_device_token' => $request->fcm_token,
                'password' => Hash::make(uniqid()),
                'email_verified_at' => Carbon::now(),
                'email_verified' => 1,
                'phone' => 0,
                'role' => 2,
                'active' => 1,
                'is_social' => 1,
            ]);
            $user->save();
            $uname = $request->first_name;
            $umail = $request->email;

            if(isset($request->device_cart_id) && $request->device_cart_id){
                \App\Models\Cart::where('device_cart_id',$request->device_cart_id)->update(['user_id'=>$user->id]);
            }

            if (config('global.server_mode') == 'local') {
                \Artisan::call("send:send_reg_email --uri=" . urlencode("Welcome to HOP") . " --uri2=" . urlencode($umail) . " --uri3=" . urlencode($uname));
            } else {
                exec("php " . base_path() . "/artisan send:send_reg_email --uri=" . urlencode("Welcome to HOP") . " --uri2=" . urlencode($umail) . " --uri3=" . urlencode($uname) . " > /dev/null 2>&1 & ");
            }
        }
        $tokenResult = $user->createToken('Personal Access Token')->accessToken;
        return $this->loginSuccess($tokenResult, $user);
    }
    public function forgot_password(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];

        $rules['email'] = 'required';
        $messages = [
            'email.required' => trans('validation.email_required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $status = "0";
            $message = trans('validation.validation_error_occured');
            $errors = $validator->messages();
        } else {

            $lemail = strtolower($request->email);
            $user = User::whereRaw("LOWER(email) = '$lemail'")->where('deleted', 0)->where(['role' => 2])->first();
            if ($user) {
                // if ($user->is_social) {
                //     $status = "0";
                //     $o_data = (object) [];
                //     $message = trans('validation.not_allowed_to_reset_password_for_social_login_account');
                //     return response()->json(['status' => $status, 'error' => $errors, 'message' => $message, 'oData' => $o_data], 200);
                // }

                $token = $this->get_user_token('password_reset_code');
                $password_reset_time = gmdate('Y-m-d H:i:s');
                $otp = (string)get_otp();
                User::where("id", $user->id)->update(['password_reset_code' => $token, 'password_reset_time' => $password_reset_time, 'password_reset_otp' => $otp]);
                $name = $user->name ?? $user->first_name . ' ' . $user->last_name;
                $res = false;
                $mailbody = view("emai_templates.forgot_mail", compact('name', 'otp'));

                if (env('APP_ENV') == 'local') {
                    \Artisan::call("send:send_forgot_email --uri=" . urlencode($user->email) . " --uri2=" . $otp . " --uri3=" . urlencode($name));
                } else {
                    exec("php " . base_path() . "/artisan send:send_forgot_email --uri=" . urlencode($user->email) . " --uri2=" . $otp . " --uri3=" . urlencode($name) . " > /dev/null 2>&1 & ");
                }


                    $res = true;

                if ($res) {
                    $message = trans('validation.we_have_e_mailed_an_otp_to_reset_your_password_please_check_your_inbox');
                    $status = "1";
                    $o_data['password_reset_code'] = $token;
                    if($request->is_web){
                        $o_data['redirect_url'] = route('otp',['token'=>$token,'email'=>$lemail]);
                    }

                } else {
                    $status = "0";
                    $o_data = (object) [];
                    $message = trans('validation.something_went_wrong');
                }

            } else {
                $o_data = (object) [];
                $message = trans('validation.user_not_exist');
            }
        }
        return response()->json(['status' => $status, 'error' => $errors, 'message' => $message, 'oData' => $o_data], 200);
    }
    public function get_user_token($type = '')
    {
        $tok = bin2hex(random_bytes(32));
        if (User::where($type, '=', $tok)->first()) {
            $this->get_user_token($type);
        }
        return $tok;
    }
    public function resend_forgot_password_otp(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];

        $rules = [
            'password_reset_code' => 'required',
        ];
        $messages = [
            'password_reset_code.required' => trans('validation.password_reset_code_required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $status = "0";
            $message = trans('validation.validation_error_occured');
            $errors = $validator->messages();
        } else {

            $user = User::where('password_reset_code', $request->password_reset_code)->first();
            if ($user) {

                $otp = (string)get_otp();
                User::where("id", $user->id)->update(['password_reset_otp' => $otp]);

                $name = $user->name ?? $user->first_name . ' ' . $user->last_name;
                $res = false;

                if (config('global.server_mode') == 'local') {
                    \Artisan::call("send:send_forgot_email --uri=" . urlencode($user->email) . " --uri2=" . $otp . " --uri3=" . urlencode($name));
                } else {
                    exec("php " . base_path() . "/artisan send:send_forgot_email --uri=" . urlencode($user->email) . " --uri2=" . $otp . " --uri3=" . urlencode($name) . " > /dev/null 2>&1 & ");
                }
                $res = true;

                if ($res) {
                    $message = trans('validation.we_have_e_mailed_an_otp_to_reset_your_password_please_check_your_inbox');
                    $status = "1";
                    $o_data['password_reset_code'] = $request->password_reset_code;

                } else {
                    $status = "0";
                    $o_data = (object) [];
                    $message = trans('validation.something_went_wrong');
                }

            } else {
                $o_data = (object) [];
                $message = trans('validation.user_not_exist');
            }
        }
        return response()->json(['status' => $status, 'error' => $errors, 'message' => $message, 'oData' => $o_data], 200);
    }
    public function reset_password(REQUEST $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];
        $errors = [];
        

        $rules = [
            'password_reset_code' => 'required',
            'otp' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ];
        $messages = [
            'password_reset_code.required' => trans('validation.password_reset_code_required'),
            'otp.required' => trans('validation.otp_required'),
            'password.required' => trans('validation.password_required'),
            'password.confirmed' => trans('validation.password_confirmed'),
            'password_confirmation.required' => trans('validation.password_confirmation_required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $status = "0";
            $message = trans('validation.validation_error_occured');
            $errors = $validator->messages();
        } else {
            $user = User::where('password_reset_code', $request->password_reset_code)->first();
            if ($user) {
                if ($request->otp == $user->password_reset_otp) {
                    $user->password = bcrypt($request->password);
                    $user->password_reset_code = '';
                    $user->password_reset_otp = 0;
                    $user->save();
                    $status = "1";
                    $message = trans('validation.password_updated_successfully');
                } else {
                    $message = trans('validation.invalid_otp');
                }
            } else {
                $message = trans('validation.invalid_otp');
                // $message = trans('validation.user_not_exist');
            }
        }
        return response()->json(['status' => $status, 'message' => $message, 'errors' => $errors, 'oData' => $o_data]);
    }
}