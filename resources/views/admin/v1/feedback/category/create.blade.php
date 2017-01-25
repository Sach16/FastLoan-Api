@extends('admin.v1.layouts.master')

@section('main')
    <h1>Create a new feedback category</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.feedback.category.store') }}" class="form-horizontal" method="post">
        {{ csrf_field() }}

        {{--Feedback Category Name--}}
        <div class="form-group col-md-8 {{ hasErrors('name', $errors) }}">
            <label class="control-label" for="inputName">Feedback Category Name</label>
            <input type="text" class="form-control" id="inputName" name="name"
                   value="{{ old('name') }}">
        </div>

        {{--Feedback Category Key--}}
        <div class="form-group col-md-8 {{ hasErrors('key', $errors) }}">
            <label class="control-label" for="inputName">Feedback Category Key</label>
            <input type="text" class="form-control" id="inputName" name="key"
                   value="{{ old('key') }}">
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <button type="reset" class="btn btn-default">Reset</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection