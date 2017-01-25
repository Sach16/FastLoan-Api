@extends('admin.v1.layouts.master')

@section('main')
    <h1>Edit feedback category</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.feedback.category.update', $feedback_category->id) }}" class="form-horizontal" method="post">
        {{ csrf_field() }}
        {{ method_field('put') }}

        {{--Feedback Category Name--}}
        <div class="form-group col-md-8 {{ hasErrors('name', $errors) }}">
            <label class="control-label" for="inputName">Feedback Category Name</label>
            <input type="text" class="form-control" id="inputName" name="name"
                   value="{{ $feedback_category->name }}">
        </div>

        {{--Feedback Category Key--}}
        <div class="form-group col-md-8 {{ hasErrors('key', $errors) }}">
            <label class="control-label" for="inputName">Feedback Category Key</label>
            <input type="text" class="form-control" id="inputName" name="key"
                   value="{{ $feedback_category->key }}">
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <a href="{{ route('admin.v1.feedback.category.index') }}" class="btn btn-default" >Back</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection