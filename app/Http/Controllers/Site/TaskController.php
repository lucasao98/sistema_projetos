<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TaskController extends Controller{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('tasks.create_task');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:projects,name',
        ]);

        $task = new Task();

        $task->name = $request->name;
        $task->finish = $request->finish;
        $task->project_id = Session::get('current_id');

        $task->save();

        return redirect()->route('tasks',Session::get('current_id'));


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        return view('tasks.edit_task',[
            'task'=> Task::find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:projects,name',
        ]);

        $task =Task::find($id);

        $task->name = $request->name;
        $task->finish = $request->finish;
        $task->project_id = Session::get('current_id');

        $task->save();

        return redirect()->route('tasks',Session::get('current_id'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $task = Task::find($id);

        $task->delete();

        return redirect()->route('tasks',Session::get('current_id'));
    }
}
