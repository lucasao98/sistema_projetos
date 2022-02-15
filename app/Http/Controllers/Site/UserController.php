<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends Controller{

    public function index(){

        if (Auth::check() === true){
            if (Session::get('isadmin') == 0) {
                return view('layouts.user');
            } elseif (Session::get('isadmin') == 1) {
                return redirect()->route('admin');
            } else {
                return redirect()->route('login');
            }
        } else {
            return redirect()->route('login');
        }
    }
}
