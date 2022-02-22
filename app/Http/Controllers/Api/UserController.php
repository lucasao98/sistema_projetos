<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        // Recebe o token do middleware
        $token = $request->bearerToken();

        /*
            Divide o token em 3 partes, o header, o payload, e a sign.
        */
        $data_token = explode(".",$token);

        // Descriptografa o payload
        $payload = base64_decode($data_token[1]);

        $user_id = json_decode($payload)->user_id;

        $projects = Project::where('user_id',$user_id)->get();

        return response()->json($projects);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        if($request->name == null && $request->email == null){
            return response()->json('Campos em branco');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:users,name',
            'email' => 'required|email|max:50|unique:users,email',
            'password' => 'required|min:8',
        ]);

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        return response()->json('Usuário alterado com sucesso!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        if($id == null){
            return response()->json('id inválido');
        }

        $user = User::find($id);

        if($user != null){
            $user->delete();
        }else{
            return response()->json('Usuário não encontrado!');
        }
    }
}
