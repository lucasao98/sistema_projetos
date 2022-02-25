<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$id){
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
                $tasks = Task::where('project_id',$id)->get([
                    'id',
                    'name',
                    'finish',
                    'created_at',
                    'updated_at'
                ]);

                return response()->json($tasks);
            }else{
                return response()->json(['O projeto não pertence ao usuário!'],401);
            }
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
    public function store(Request $request,$id){
        if($request->name == null && $request->finish == null){
            return response()->json('Campos em branco');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:users,name',
            'finish' => 'required|integer',
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
                $task = new Task();

                $task->name = $request->name;
                $task->finish = $request->finish;
                $task->project_id = $project->id;

                $task->save();

                return response()->json(['Tarefa adicionada com sucesso!']);

            }else{
                return response()->json(['O projeto não pertence ao usuário!'],401);
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
        if($request->name == null && $request->finish == null){
            return response()->json('Campos em branco');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:users,name',
            'finish' => 'required|integer',
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

         if($data_token[2] == $sign){
            $payload = base64_decode($data_token[1]);

            $user_id = json_decode($payload)->user_id;

            $project = Project::where('user_id',$user_id)->first();

            if($user_id === $project->user_id){
                $task = Task::find($id);
                if($task){
                    $task->name = $request->name;
                    $task->finish = $request->finish;

                    $task->save();

                    return response()->json(['Tarefa Alterada com sucesso!']);
                }else{
                    return response()->json(['Tarefa não encontrada!']);
                }
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

            $project = Project::where('user_id',$user_id)->first();

            if($user_id === $project->user_id){
                $task = Task::find($id);
                if($task){
                    $task = Task::where('project_id',$project->id)->first();

                    $task->delete();

                    return response()->json(['Tarefa Deletada!']);
                }else{
                    return response()->json(['Tarefa não encontrada!']);
                }
            }else{
                return response()->json(['O projeto não pertence ao usuário!'],401);
            }
        }else{
            return response()->json(['Token is invalid'],403);
        }
    }
}
