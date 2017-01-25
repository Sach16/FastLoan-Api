@extends('admin.v1.layouts.master')

@section('main')
    <h1>Add new holiday</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.teams.calendars.store', $team->id) }}" class="form-horizontal" method="post">
        {{ csrf_field() }}

        {{--Description--}}
        <div class="form-group col-md-8 {{ hasErrors('description', $errors) }}">
            <label class="control-label" for="inputDescription">Description</label>
            <input type="text" class="form-control" id="inputDescription" name="description" value="{{ old('description') }}">
        </div>

        {{--Date--}}
        <div class="form-group col-md-8 {{ hasErrors('date', $errors) }}">
            <label class="control-label" for="inputDate">Date</label>
            <input class="form-control" id="inputDate" type="text" data-date
                   name="date"
                   value="{{ old('date') }}">
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