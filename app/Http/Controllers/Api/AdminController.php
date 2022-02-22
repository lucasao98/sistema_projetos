<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $users = User::all();

        // var_dump(json_decode($pload)->user_id);

        if($users == null){
            return response()->json(['Não existem usuários cadastrados']);
        }else{
            return response()->json($users);
        }
    }

    private function generateToken($id){
        $key = getenv('JWT_SECRET');

        // Header Token
        $header = [
            'typ' =>'JWT',
            'alg' => 'HS256'
        ];

        // Payload - Content
        $payload = [
            'exp' => 3600,
            'user_id' => $id
        ];

        // JSON
        $header = json_encode($header);
        $payload = json_encode($payload);

        // Base 64
        $header = base64_encode($header);
        $payload = base64_encode($payload);

        //Sign
        $sign = hash_hmac('sha256',$header . "." . $payload,$key,true);
        $sign = base64_encode($sign);

        $token = $header . '.' . $payload . '.' . $sign;

        return $token;
    }

    public function login(Request $request){
        $validated = $request->validate([
            'email' => 'required|email|max:50|exists:users,email',
            'password' => 'required|min:8',
        ]);

        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        if($request->name == null && $request->email == null){
            return response()->json('Campos em branco');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:users,name',
            'email' => 'required|email|max:50|unique:users,email',
            'password' => 'required|min:8',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->superuser = 0;

        $user->save();

        return response()->json('Usuário cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        if($id == null){
            return response()->json('id inválido');
        }

        $user = User::find($id);

        if($user != null){
            return response()->json($user);
        }else{
            return response()->json('Usuário não encontrado!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
            return response()->json('Usuário deletado!');
        }else{
            return response()->json('Usuário não encontrado!');
        }
    }

    public function givePermission($id){
        if($id == null){
            return response()->json('id inválido');
        }

        $user = User::find($id);

        if($user){
            $user->update(['superuser'=> 1]);

            return response()->json('Usuário promovido para admin!');
        }
    }

    public function cancelPermission($id){
        if($id == null){
            return response()->json('id inválido');
        }

        $user = User::find($id);

        if($user){
            $user->update(['superuser'=> 0]);

            return response()->json('Usuário promovido para admin!');
        }
    }
}
