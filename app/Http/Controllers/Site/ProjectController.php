<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProjectController extends Controller{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('projects.table_projects',[
            'projects' => Project::with('user')->where('user_id',Session::get('id'))->get(),
        ]);
    }

    public function showForm(){
        return view('projects.create_project');
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
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $project = new Project();
        $project->name = $request->name;
        $project->start_date = $request->start_date;
        $project->end_date = $request->end_date;
        $project->user_id = Session::get('id');

        $project->save();

        return redirect()->route('table');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        return view('projects.update_project',[
            'project' => Project::find($id)
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
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $project = Project::find($id);
        $project->name = $request->name;
        $project->start_date = $request->start_date;
        $project->end_date = $request->end_date;

        $project->save();

        return redirect()->route('table');

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $project = Project::find($id);

        $project->delete();

        return redirect()->route('table');
    }

    public function showTasks($id){
        $project = Project::find($id);

        Session::put('current_id',$project['id']);

        return view('projects.show_project',[
            'tasks' =>Task::with('project')->where('project_id',$id)->get(),
            'project_name' => $project['name'],
        ]);
    }
}
