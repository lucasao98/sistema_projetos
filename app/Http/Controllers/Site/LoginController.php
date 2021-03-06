<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;

class LoginController extends Controller{
    public function index(){
        if(Auth::check() == true){
            if(Session::get('isadmin') == 1){
                return redirect()->route('admin');
            }else{
                return redirect()->route('user');
            }
        }
        return view('layouts.login');
    }

    public function login(Request $request){

        $validated = $request->validate([
            'email' => 'required|email|max:50|exists:users,email',
            'password' => 'required|min:8'
        ]);

        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        $user = User::where('email',$request->email)->first();
        Session::put('isadmin',$user['superuser']);
        Session::put('id',$user['id']);

        if(Auth::attempt($data)){
            if(Session::get('isadmin') == 1){
                return redirect()->route('admin');
            }else{
                return redirect()->route('user');
            }
        }else{
            return redirect()->route('login');
        }
    }

    public function logout(){
        Auth::logout();
        Session::forget('isadmin');
        Session::flush();

        return redirect()->route('login');
    }
}
