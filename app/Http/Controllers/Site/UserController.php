<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

    public function showForm(){
        if(Session::get('isadmin') == 1){
            return view('users.showForm');
        }elseif(Session::get('isadmin') == 0){
            return redirect()->route('user');
        }else{
            return redirect()->route('login');
        }
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:users,name',
            'email' => 'required|email|max:50|unique:users,email',
            'password' => 'required|min:8',
        ]);

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->superuser = 0;

        $user->save();

        return redirect()->route('admin');
    }

    public function show(){
        if(Session::get('isadmin') == 1){
            return view('users.showUsers',[
                'users' => User::where('superuser',0)->get()
            ]);
        }elseif(Session::get('isadmin') == 0){
            return redirect()->route('user');
        }else{
            return redirect()->route('login');
        }
    }

    public function destroy($id){
        $user = User::find($id);

        $user->delete();

        return redirect()->route('users');
    }
}
