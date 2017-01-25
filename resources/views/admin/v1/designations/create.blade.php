@extends('admin.v1.layouts.master')

@section('main')
    <h1>Add new designation</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.designations.store') }}" class="form-horizontal" method="post">
        {{ csrf_field() }}

        {{--Name--}}
        <div class="form-group col-md-8 {{ hasErrors('name', $errors) }}">
            <label class="control-label" for="inputDesignationName">Name</label>
            <input type="text" class="form-control" id="inputDesignationName" name="name" value="{{ old('name') }}">
        </div>

        {{--Description--}}
        <div class="form-group col-md-8 {{ hasErrors('description', $errors) }}">
            <label class="control-label" for="inputDesignationDescription">Description</label>
            <textarea name="description" id="inputDesignationDescription" cols="30" rows="5" class="form-control">{{ old('description') }}</textarea>
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