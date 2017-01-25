@extends('admin.v1.layouts.master')

@section('main')
    <h1>Edit State</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.states.update', $state->id) }}" class="form-horizontal" method="post">
        {{ csrf_field() }}
        {{ method_field('put') }}

        {{--Name--}}
        <div class="form-group col-md-8 {{ hasErrors('name', $errors) }}">
            <label class="control-label" for="inputStateName">State Name</label>
            <input type="text" class="form-control" id="inputStateName" name="name" value="{{ $state->name }}">
        </div>

        {{--Description--}}
        <div class="form-group col-md-8 {{ hasErrors('description', $errors) }}">
            <label class="control-label" for="inputStateDescription">State Description</label>
            <textarea name="description" id="inputStateDescription" cols="30" rows="5" class="form-control">{{ $state->description }}</textarea>
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <a href="{{ route('admin.v1.states.index') }}" class="btn btn-default">Back</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection