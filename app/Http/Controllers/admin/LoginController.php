<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Categories;
use DB;

class LoginController extends Controller
{
    //
    public function clear()
    {
        \Artisan::call('optimize');
        \Artisan::call('optimize:clear');
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('config:cache');
        \Artisan::call('view:clear');
        \Artisan::call('config:clear');

        return redirect()->back();
    }
    public function login()
    {
        if (Auth::check() && (Auth::user()->role == '1')) {
            return redirect()->route('admin.dashboard');
        }
        // echo Hash::make('Hello@1985');
        return view('admin.login');
    }
    public function check_login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required',
        ]);

        // Validate request
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            if (Auth::check() && (Auth::user()->role == '1')) {
                $request->session()->put('user_id',Auth::user()->id);
                if($request->timezone){
                    $request->session()->put('user_timezone',$request->timezone);
                }
                return response()->json(['success' => true, 'message' => "Logged in successfully."]);
            } else {
                return response()->json(['success' => false, 'message' => "Invalid Credentials!"]);
            }

        }

        return response()->json(['success' => false, 'message' => "Invalid Credentials!"]);
    }
    public function logout(){
        session()->pull("user_id");
        Auth::logout();
        return redirect()->route('admin.login');
    }
    public function get_category(Request $request){
        $ctid = $request->division;
      $query= Categories::select('id','name')->orderBy('sort_order','asc')->where(['deleted'=>0,'active'=>1,])->where('division_id',$ctid)->get();
        $data=$query->toArray();
        if($query->count()==0)
        { $data ="0"; }
        echo  json_encode($data);
    }
}
