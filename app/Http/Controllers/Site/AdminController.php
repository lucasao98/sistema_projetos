<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller{
    public function index(){
        if(Auth::check() === true){
            if(Session::get('isadmin') == 1){
                return view('layouts.admin');
            }elseif(Session::get('isadmin') == 0){
                return redirect()->route('user');
            }else{
                return redirect()->route('login');
            }
        }else{
            return redirect()->route('login');
        }
    }
}


