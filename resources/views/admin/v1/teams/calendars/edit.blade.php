@extends('admin.v1.layouts.master')

@section('main')
    <h1>Edit Holiday</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.teams.calendars.update', [
        'teams' => $team->id, 'calendars' => $calendar->id
    ]) }}" class="form-horizontal" method="post">
        {{ csrf_field() }}
        {{ method_field('put') }}
        {{ method_field('put') }}

        {{--Description--}}
        <div class="form-group col-md-8 {{ hasErrors('description', $errors) }}">
            <label class="control-label" for="inputDescription">Description</label>
            <input type="text" class="form-control" id="inputDescription" name="description" value="{{ $calendar->description }}">
        </div>

        {{--Date--}}
        <div class="form-group col-md-9 {{ hasErrors('date', $errors) }}">
            <label class="control-label" for="editCalendarDate">Date</label>
            <input type="hidden" id="selected-editCalendarDate" value="{{ date('m-d-Y',strtotime($calendar->present()->dateInput)) }}">
            <input class="form-control" id="editCalendarDate" type="text"
                   name="date" data-date
                   value="{{ date('d-m-Y',strtotime($calendar->present()->dateInput)) }}">
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                 <a href="{{ route('admin.v1.teams.calendars.index', $team->id) }}" class="btn btn-default">Back</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection
