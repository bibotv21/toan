<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
class Login extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->except('logout');
        $this->middleware('guest:user')->except('logout');
    }
    //
    public function getLogin(){
    	return view('admin.login');
    }

    public function postLogin(Request $request){
    	$ar=['account'=>$request->ac,'password'=>$request->pw];


    	//    	if(Auth::attempt($ar)){
     //            // $data['about_admin'] = DB::table('admins')->where('account',$ar['account'])->first();
    	// 	return redirect()->intended('admin/home');
    	// }else{
    	// 	return back()->withInput()->with('error','Tài khoản hoặc mật khẩu không đúng');
    	// }
        Auth::shouldUse('admin');
        if (Auth::attempt($ar)) {
            // $details = Auth::guard('admin')->user();
            // $user = $details['original'];
            // dd(Auth::user());-
            return redirect()->intended('admin/');
        } else {
            return back()->withInput()->with('error','Tài khoản hoặc mật khẩu không đúng');
        }
    }
}
