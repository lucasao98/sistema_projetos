<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
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

        $data_users = [];

        foreach($users as $user){
            array_push($data_users,[
                "name" => $user->name,
                "email" => $user->email,
                "bearer_token" => $this->generateToken($user->id),

            ]);
        }

        if($users == null){
            return response()->json(['Não existem usuários cadastrados']);
        }else{
            return response()->json($data_users);
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
            'exp' => time() + 3600,
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
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
        $exp = (json_decode($payload)->exp);

        $date_now = time();

        // Recebe o header e o payload do $token e cria a sign.
        $sign = hash_hmac('sha256',$data_token[0] . "." . $data_token[1],$key,true);
        $sign = base64_encode($sign);

        if($date_now >= $exp){
            return response()->json(['status'=>'Token is Expired'],403);
        }

        if($data_token[2] == $sign){
            $payload = base64_decode($data_token[1]);

            $user_id = json_decode($payload)->user_id;

            $user = User::find($user_id);

            if($user->superuser == 1){
                if($request->name == null && $request->email == null && $request->password == null){
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
            }else{
                return response()->json(['Unauthorized!'],401);
            }

        }else{
            return response()->json(['Token is invalid'],403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id){
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
        $exp = (json_decode($payload)->exp);

        $date_now = time();

        // Recebe o header e o payload do $token e cria a sign.
        $sign = hash_hmac('sha256',$data_token[0] . "." . $data_token[1],$key,true);
        $sign = base64_encode($sign);

        if($date_now >= $exp){
            return response()->json(['status'=>'Token is Expired'],403);
        }

        if($id == null){
            return response()->json('id inválido');
        }

        if($data_token[2] == $sign){
            $payload = base64_decode($data_token[1]);

            $user_id = json_decode($payload)->user_id;

            $user = User::find($user_id);

            if($user->superuser == 1){
                return response()->json($user);
            }else{
                return response()->json(['Unauthorized!'],401);
            }

        }else{
            return response()->json(['Token is invalid'],403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
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
        $exp = (json_decode($payload)->exp);

        $date_now = time();

        // Recebe o header e o payload do $token e cria a sign.
        $sign = hash_hmac('sha256',$data_token[0] . "." . $data_token[1],$key,true);
        $sign = base64_encode($sign);

        if($date_now >= $exp){
            return response()->json(['status'=>'Token is Expired'],403);
        }

        if($id == null){
            return response()->json('id inválido');
        }

        if($data_token[2] == $sign){
            $payload = base64_decode($data_token[1]);

            $user_id = json_decode($payload)->user_id;

            $user = User::find($user_id);

            if($user->superuser == 1){
                if($request->name == null && $request->email == null){
                    return response()->json('Campos em branco');
                }

                $validated = $request->validate([
                    'name' => 'required|string|max:50',
                    'email' => 'required|email|max:50',
                ]);

                $user->name = $request->name;
                $user->email = $request->email;

                $user->save();

                return response()->json('Usuário alterado!');

            }else{
                return response()->json(['Unauthorized!'],401);
            }
        }else{
            return response()->json(['Token is invalid'],403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id){
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
        $exp = (json_decode($payload)->exp);

        $date_now = time();

        // Recebe o header e o payload do $token e cria a sign.
        $sign = hash_hmac('sha256',$data_token[0] . "." . $data_token[1],$key,true);
        $sign = base64_encode($sign);

        if($date_now >= $exp){
            return response()->json(['status'=>'Token is Expired'],403);
        }

        if($id == null){
            return response()->json('id inválido');
        }

        if($data_token[2] == $sign){
            $payload = base64_decode($data_token[1]);

            $user_id = json_decode($payload)->user_id;

            $user = User::find($user_id);

            if($user->superuser == 1){
                $user->delete();
                return response()->json('Usuário deletado!');
            }else{
                return response()->json(['Unauthorized!'],401);
            }

        }else{
            return response()->json(['Token is invalid'],403);
        }
    }

    public function givePermission(Request $request,$id){
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
        $exp = (json_decode($payload)->exp);

        $date_now = time();

        // Recebe o header e o payload do $token e cria a sign.
        $sign = hash_hmac('sha256',$data_token[0] . "." . $data_token[1],$key,true);
        $sign = base64_encode($sign);

        if($date_now >= $exp){
            return response()->json(['status'=>'Token is Expired'],403);
        }

        if($id == null){
            return response()->json('id inválido');
        }

        if($data_token[2] == $sign){
            $payload = base64_decode($data_token[1]);

            $user_id = json_decode($payload)->user_id;

            $user = User::find($user_id);

            if($user->superuser == 1){
                $user->update(['superuser'=> 1]);

                return response()->json('Usuário promovido para admin!');

            }else{
                return response()->json(['Unauthorized!'],401);
            }

        }else{
            return response()->json(['Token is invalid'],403);
        }
    }

    public function cancelPermission(Request $request,$id){
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
        $exp = (json_decode($payload)->exp);

        $date_now = time();

        // Recebe o header e o payload do $token e cria a sign.
        $sign = hash_hmac('sha256',$data_token[0] . "." . $data_token[1],$key,true);
        $sign = base64_encode($sign);

        if($date_now >= $exp){
            return response()->json(['status'=>'Token is Expired'],403);
        }

        if($id == null){
            return response()->json('id inválido');
        }

        if($data_token[2] == $sign){
            $payload = base64_decode($data_token[1]);

            $user_id = json_decode($payload)->user_id;

            $user = User::find($user_id);

            if($user->superuser == 1){
                $user->update(['superuser'=> 0]);

                return response()->json('Permissão removida!');

            }else{
                return response()->json(['Unauthorized!'],401);
            }

        }else{
            return response()->json(['Token is invalid'],403);
        }
    }

    public function allProjects(Request $request){
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
        $exp = (json_decode($payload)->exp);

        $date_now = time();

        // Recebe o header e o payload do $token e cria a sign.
        $sign = hash_hmac('sha256',$data_token[0] . "." . $data_token[1],$key,true);
        $sign = base64_encode($sign);

        if($date_now >= $exp){
            return response()->json(['status'=>'Token is Expired'],403);
        }

        if($data_token[2] == $sign){
            $payload = base64_decode($data_token[1]);

            $user_id = json_decode($payload)->user_id;

            $user = User::find($user_id);

            if($user->superuser == 1){
                $data_user = [];

                $projects = Project::where('user_id',$user_id)->get();

                foreach($projects as $project){
                    array_push($data_user,[
                        'project_name' => $project->name,
                        'start_date' => $project->start_date,
                        'end_date' => $project->end_date,
                        'owner' => User::where('id',$user_id)->first()->name,
                        'tasks' => Task::where('project_id',$project->id)->get([
                            'name',
                            'finish',
                            'created_at',
                            'updated_at'
                        ])
                    ]);
                }

                return response()->json($data_user);
            }else{
                return response()->json(['Unauthorized!'],401);
            }

        }else{
            return response()->json(['Token is invalid'],403);
        }
    }
}
