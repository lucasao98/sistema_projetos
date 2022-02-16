@extends('layouts.admin')

@section('content')
<div class="row mt-2">
    <div class="col">
        <h1>{{$project}}</h1>
        <h2 class="mt-4 mb-4">Tasks</h2>
            <a class="btn btn-success" href="{{ route('task')}}"><i class="fa-solid fa-circle-plus"></i></a>
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
            <td>{{$task->finish}}</td>
            <form action="" method="get">
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
