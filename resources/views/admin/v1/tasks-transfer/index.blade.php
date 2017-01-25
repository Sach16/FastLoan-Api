@extends('admin.v1.layouts.master')

@section('main')
    <h1>Transfer ownership of tasks</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.tasks-transfer.store') }}" class="form-horizontal" method="post">
        {{ csrf_field() }}

        {{--From--}}
        <div class="form-group col-md-8 {{ hasErrors('from', $errors) }}">
            <label class="control-label" for="inputFrom">From</label>
            <select name="from" id="inputFrom" class="form-control" data-owners>
                <option disabled selected>Select user</option>
            </select>
        </div>

        {{--To--}}
        <div class="form-group col-md-8 {{ hasErrors('to', $errors) }}">
            <label class="control-label" for="inputTo">To</label>
            <select name="to" id="inputTo" class="form-control" data-owners>
                <option disabled selected>Select user</option>
            </select>
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <button type="reset" class="btn btn-default" data-back>Back</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection
