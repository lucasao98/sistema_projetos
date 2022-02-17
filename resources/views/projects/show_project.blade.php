@extends('layouts.admin')

@section('content')
<div class="row mt-2">
    <div class="col">
        <h1>{{$project_name}}</h1>
        <hr>
        <a class="btn btn-success mt-2" href="{{ route('task')}}"><i class="fa-solid fa-circle-plus"></i></a>
        <span>Add Task</span>
        </form>
    </div>
</div>
<table class="table table-hover table-striped mt-5">
    <thead>
      <tr>
        <th scope="col">Name</th>
        <th scope="col">Status</th>
        <th scope="col">Actions</th>
      </tr>
    </thead>
    <tbody>
        @foreach ($tasks as $task)
        <tr>
            <td>{{$task->name}}</td>
            <td>
                @if ($task->finish == 1)
                    Finish
                @elseif ($task->finish == 0)
                    Working
                @endif
            </td>
            <form action="{{ route('edit.task',$task->id)}}" method="get">
                @csrf
                <td><button class="btn btn-primary" type="submit"><i class="fa-solid fa-pen-to-square"></i></button></td>
            </form>
            <form action="{{ route('delete.task',$task->id)}}" method="post">
                @csrf
                @method('delete')
                <td><button class="btn btn-danger" type="submit"><i class="fa-regular fa-trash-can"></i></button></td>
            </form>
        </tr>
        @endforeach


    </tbody>
  </table>
  @endsection
