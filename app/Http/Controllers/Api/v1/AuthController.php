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
}