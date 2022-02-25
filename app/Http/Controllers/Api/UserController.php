<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
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

        // Se a sign criada for igual a contida no token, então é realizada a descriptografia.
        if($data_token[2] == $sign){
            $payload = base64_decode($data_token[1]);

            $user_id = json_decode($payload)->user_id;

            $data_user = [];

            $projects = Project::where('user_id',$user_id)->get();

            foreach($projects as $project){
                array_push($data_user,[
                    'project_name' => $project->name,
                    'start_date' => $project->start_date,
                    'end_date' => $project->end_date,
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
}
