@extends('admin.v1.layouts.master')

@section('main')
    <h1>Edit designation</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.designations.update', $designation->id) }}" class="form-horizontal" method="post">
        {{ csrf_field() }}
        {{ method_field('put') }}

        {{--Name--}}
        <div class="form-group col-md-8 {{ hasErrors('name', $errors) }}">
            <label class="control-label" for="inputDesignationName">Name</label>
            <input type="text" class="form-control" id="inputDesignationName" name="name" value="{{ $designation->name }}">
        </div>

        {{--Description--}}
        <div class="form-group col-md-8 {{ hasErrors('description', $errors) }}">
            <label class="control-label" for="inputDesignationDescription">Description</label>
            <textarea name="description" id="inputDesignationDescription" cols="30" rows="5" class="form-control">{{ $designation->description }}</textarea>
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <a href="{{route('admin.v1.designations.index')}}" class="btn btn-default" >Back</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection