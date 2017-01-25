@extends('admin.v1.layouts.master')

@section('main')
    <h1>Team Attendance History <small>({{ $date->format('F - Y') }})</small></h1>
    @include('admin.v1.layouts.partials._messages')
    <form action="{{ route('admin.v1.teams.attendances.index', $team->id) }}"
          class="form-horizontal">
        {{--Month Selector--}}
        <div class="form-group col-md-8 {{ hasErrors('month', $errors) }}">
            <label class="control-label" for="inputMonth">Month Selector</label>
            <div class="input-group">
                <input type="text" class="form-control" id="inputMonth" name="month" data-monthSelector value="{{ $date->format('m - Y') }}">
                <span class="input-group-btn">
                    <button class="btn btn-info" type="submit">Go</button>
                </span>
            </div>
        </div>
    </form>
    <hr>
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th>Member Name</th>
            <th>Present</th>
        </tr>
        </thead>
        <tbody>
        @foreach($team->members as $member)
            <tr>
                <td>{{ $member->present()->name }}</td>
                <td>{{ $member->attendances->count() }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection