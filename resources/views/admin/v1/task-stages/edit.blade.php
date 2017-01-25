@extends('admin.v1.layouts.master')

@section('main')
    <h1>Edit task stage</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.task-stages.update', $stage->id) }}" method="post"
          class="form-horizontal">
        {{ csrf_field() }}
        {{ method_field('put') }}

        {{--Label--}}
        <div class="form-group col-md-8 {{ hasErrors('label', $errors) }}">
            <label class="control-label" for="inputLabel">Label</label>
            <input type="text" class="form-control" id="inputLabel" name="label" value="{{ $stage->label }}">
        </div>

        {{--Key--}}
        <div class="form-group col-md-8">
            <label class="control-label" for="inputLabel">Key</label>
            <input type="text" class="form-control" id="inputLabel" value="{{ $stage->key }}" readonly>
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <a href="{{ route('admin.v1.task-stages.index') }}" class="btn btn-default">Back</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection