<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;

class UserController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        // Recebe o token do middleware
        $token = $request->bearerToken();
        $key = getenv('JWT_SECRET');

        /*
            Verifica se o token está no padrão correto, sendo header.payload.sign
            Caso o token não tenha essa estrutura, o token está inválido.
        */

        $data_token = explode(".",$token);

        if(count($data_token) < 3){
            return response()->json(['Token is invalid'],403);
        }
        $payload = base64_decode($data_token[1]);
        $exp_sec = (json_decode($payload)->exp);

        $time_exp = strtotime("12:00:00");;

        $date_now = date('H:i:s');

        var_dump($time_exp);
        // Recebe o header e o payload do $token e cria a sign.
        $sign = hash_hmac('sha256',$data_token[0] . "." . $data_token[1],$key,true);
        $sign = base64_encode($sign);

        if($date_now >= $time_exp){
            return response()->json(['status'=>'Token is Expired']);
        }

        // Se a sign criada for igual a contida no token, então é realizada a descriptografia.
        if($data_token[2] == $sign){
            $payload = base64_decode($data_token[1]);

            $user_id = json_decode($payload)->user_id;

            $projects = Project::where('user_id',$user_id)->get();

            return response()->json($projects);
        }else{
            return response()->json(['Token is invalid'],403);
        }
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