@extends('layouts.admin')

@section('content')
<div class="container">
    <main>
        <h4 class="mb-3 mt-3">Register Project</h4>
        <form class="needs-validation" novalidate action="{{ route('store.project')}}" method="POST">
            @csrf
            <div class="row g-3">
              <div class="col-sm-6">
                <label for="firstName" class="form-label">Name</label>
                <input type="text" class="form-control mb-2 @error('name') is-invalid @enderror" name="name" value="{{ @old('name')}}" required>
                @error('name')
                <div class="invalid-feedback">
                   {{$message}}
                </div>
                @enderror

                <label for="formGroupExampleInput" class="form-label">End date</label>
                <input type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" value="{{ @old('start_date')}}" required>
                @error('start_date')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
              </div>

              <div class="col-md-4">
                <label for="formGroupExampleInput" class="form-label">Start date</label>
                <input type="date" class="form-control @error('end_date') is-invalid @enderror" name="end_date" value="{{ @old('end_date')}}" required>
                @error('end_date')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
              </div>

              <button class="w-25 mt-3 btn btn-success btn-lg" type="submit">Save</button>
            </div>
        </form>
    </main>
  </div>
@endsection
