<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller{
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
        if($request->name == null && $request->start_date == null && $request->end_date == null){
            return response()->json('Campos em branco');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:users,name',
            'start_date' => 'required|date|max:50|date_format:Y-m-d',
            'end_date' => 'required|date|max:50|date_format:Y-m-d',
        ]);

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

            $project = new Project();

            $project->name = $request->name;
            $project->start_date = $request->start_date;
            $project->end_date = $request->end_date;
            $project->user_id = $user_id;

            $project->save();

            return response()->json(['Projeto Cadastrado com sucesso!']);
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
        if($request->name == null && $request->start_date == null && $request->end_date == null){
            return response()->json('Campos em branco');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:users,name',
            'start_date' => 'required|date|max:50|date_format:Y-m-d',
            'end_date' => 'required|date|max:50|date_format:Y-m-d',
        ]);

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

            $project = Project::find($id);

            if($user_id === $project->user_id){
                $project->name = $request->name;
                $project->start_date = $request->start_date;
                $project->end_date = $request->end_date;

                $project->save();

                return response()->json(['Projeto Alterado com sucesso!']);
            }else{
                return response()->json(['O projeto não pertence ao usuário!'],401);
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

            $project = Project::find($id);

            if($user_id === $project->user_id){
                $project->delete();

                return response()->json(['Projeto Deletado!']);
            }else{
                return response()->json(['O projeto não pertence ao usuário!'],401);
            }
        }else{
            return response()->json(['Token is invalid'],403);
        }


    }
}
