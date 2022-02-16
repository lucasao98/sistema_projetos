@extends('layouts.admin')

@section('content')
<table class="table table-hover table-striped mt-5">
    <thead>
      <tr>
        <th scope="col">Name</th>
        <th scope="col">Email</th>
        <th scope="col">Created_at</th>
        <th scope="col">Actions</th>
      </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->created_at}}</td>
                <td>
                    <a class="btn btn-primary" href="http://">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                </td>
                <form action="{{ route('delete.user',$user->id)}}" method="post">
                    @csrf
                    @method('delete')
                    <td><button class="btn btn-danger" type="submit"><i class="fa-regular fa-trash-can"></i></button></td>
                </form>
            </tr>
        @endforeach

    </tbody>
  </table>
  @endsection
