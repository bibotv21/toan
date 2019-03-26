<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class HomeController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth:admin');
    }
    //
    public function getHome(){
    	return view('admin.index');
    }

    public function getLogout(){
    	Auth::logout();
    	return redirect()->intended('admin/login');
    }

    
}
