@extends('layouts.admin')

@section('content')
<div class="container">
    <main>
        <h4 class="mb-3 mt-3">Register User</h4>
        <form class="needs-validation" novalidate action="{{ route('store.user')}}" method="POST">
            @csrf
            <div class="row g-3">
              <div class="col-sm-6">
                <label for="firstName" class="form-label">Name</label>
                <input type="text" class="form-control mb-2 @error('name') is-invalid @enderror" name="name" placeholder="" value="{{ @old('name')}}" required>
                @error('name')
                <div class="invalid-feedback">
                   {{$message}}
                </div>
                @enderror
              </div>

              <div class="col-md-4">
                <label for="formGroupExampleInput" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ @old('email')}}" required>
                @error('email')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
              </div>

              <div class="col-md-6">
                <label for="formGroupExampleInput" class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror w-50" name="password" required>
                @error('password')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
              </div>

            </div>

            <div class="col-md-8">
                <button class="w-25 mt-3 btn btn-success btn-lg" type="submit">Save</button>
            </div>


        </form>
    </main>

  </div>
@endsection
