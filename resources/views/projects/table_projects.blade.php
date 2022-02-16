@extends('layouts.admin')

@section('content')
<table class="table table-hover table-striped mt-5">
    <thead>
      <tr>
        <th scope="col">Name</th>
        <th scope="col">Start_Date</th>
        <th scope="col">End_Date</th>
        <th scope="col">Project Owner</th>
        <th scope="col">Actions</th>
      </tr>
    </thead>
    <tbody>
        @php
            //dd($projects);
        @endphp
        @foreach($projects as $project)
            <tr>
                <td>{{$project->name}}</td>
                <td>{{$project->start_date}}</td>
                <td>{{$project->end_date}}</td>
                <td>{{$project->user->name}}</td>
                <td>
                    <a class="btn btn-primary" href="http://">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                </td>
                <form action="{{ route('delete.project',$project->id)}}" method="post">
                    @csrf
                    @method('delete')
                    <td><button class="btn btn-danger" type="submit"><i class="fa-regular fa-trash-can"></i></button></td>
                </form>
            </tr>
        @endforeach

    </tbody>
  </table>
  @endsection
