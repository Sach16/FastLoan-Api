@extends('admin.v1.layouts.master')

@section('main')
    <h1>Add new task stage</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.task-stages.store') }}" class="form-horizontal" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="uuid" value="{{ uuid() }}">
        {{--Label--}}
        <div class="form-group col-md-8 {{ hasErrors('label', $errors) }}">
            <label class="control-label" for="inputLabel">Label</label>
            <input type="text" class="form-control" id="inputLabel" name="label" value="{{ old('label') }}">
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