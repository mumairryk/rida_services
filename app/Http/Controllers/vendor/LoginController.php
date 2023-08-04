<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    public function login()
    {
        if (Auth::check() && (Auth::user()->role == '1')) {
            return redirect()->route('vendor.dashboard');
        }
        // echo Hash::make('Hello@1985');
        return view('vendor.login');
    }
    public function check_login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required',
        ]);

        // Validate request
        $roles = [1, 2, 3, 4, 5, 6];
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            if (Auth::check() && (Auth::user()->user_type_id == '1' || Auth::user()->user_type_id == '2' || Auth::user()->user_type_id == '3' || Auth::user()->user_type_id == '4' || Auth::user()->user_type_id == '5' || Auth::user()->user_type_id == '6')) {
                $request->session()->put('user_id',Auth::user()->id);
                if($request->timezone){
                    $request->session()->put('user_timezone',$request->timezone);
                }
                return response()->json(['success' => true, 'message' => "Logged in successfully."]);
            }elseif (Auth::check() && (Auth::user()->active == '0')) {
                return response()->json(['success' => false, 'message' => "You are blocked by admin!"]);
            }

        }

        return response()->json(['success' => false, 'message' => "Invalid Credentials!"]);
    }
    public function forgotpassword()
    {
        if (Auth::check() && (Auth::user()->role == '1')) {
            return redirect()->route('vendor.dashboard');
        }
        return view('vendor.forgot');
    }
    public function check_user(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email']
        ]);

        $user = User::where(['email' => $request->email,'role'=>'3'])->get();
        if($user->isNotEmpty()) {

            $token = $this->get_user_token('password_reset_code');
            $password_reset_time = gmdate('Y-m-d H:i:s');

                User::where("email",'=',$request->email)->update(['password_reset_code' =>$token,'password_reset_time'=>$password_reset_time]);
                $link = url('reset_password/'.$token);
                $mailbody =  view("emai_templates.reset_password",compact('link'));

                if(send_email($request->email,'Reset Your Moda Password',$mailbody)){
                    $status = "1";
                    $message = "A link has been sent to your email to reset your password";
                }else{
                    $status = "0";
                    $message = "Email not sent";
                }
             
            return response()->json(['success' => true, 'message' => "We have e-mailed your password reset link. Please check your inbox."]);
        }
        else
        {
            return response()->json(['success' => false, 'message' => "E-mail not exist"]);
        }
    }
    public function get_user_token($type = '')
    {
        $tok = bin2hex(random_bytes(32));
        if (User::where($type, '=', $tok)->first()) {
            $this->get_user_token($type);
        }
        return $tok;
    }
    public function logout(){
        session()->pull("user_id");
        Auth::logout();
        return redirect()->route('vendor.login');
    }
}
