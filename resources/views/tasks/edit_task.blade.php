@extends('layouts.admin')

@section('content')
<div class="container">
    <main>
        <h4 class="mb-3 mt-3">Edit Task</h4>
        <form class="needs-validation" novalidate action="{{ route('update.task',$task->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
              <div class="col-sm-6">
                <label for="firstName" class="form-label">Name</label>
                <input type="text" class="form-control mb-2 @error('name') is-invalid @enderror" name="name" value="{{$task->name}}" required>
                @error('name')
                <div class="invalid-feedback">
                   {{$message}}
                </div>
                @enderror
                <div class="bg bg-light mt-4">
                    <fieldset class="row mb-3">
                        <legend class="col-form-label col-sm-2 pt-0">Finish</legend>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="finish" id="gridRadios1" value="1" checked>
                                <label class="form-check-label" for="gridRadios1">
                                    Yes
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="finish" id="gridRadios2" value="0">
                                <label class="form-check-label" for="gridRadios2">
                                    No
                                </label>
                            </div>
                        </div>
                    </fieldset>
                </div>
              <button class="w-25 mt-3 btn btn-success btn-lg" type="submit">Save</button>
            </div>
        </form>
    </main>
  </div>
@endsection
