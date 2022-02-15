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
        @foreach($projects as $project)
            <tr>
                <td>{{$project->name}}</td>
                <td>{{$project->start_date}}</td>
                <td>{{$project->end_date}}</td>
                <td>{{}}</td>
                <td><i class="fa-solid fa-eye"></i></td><td><i class="fa-solid fa-eye"></i></td>
            </tr>
        @endforeach

    </tbody>
  </table>
  @endsection
