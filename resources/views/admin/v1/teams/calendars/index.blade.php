@extends('admin.v1.layouts.master')

@section('main')
    <h1>Team holidays</h1>
    @include('admin.v1.layouts.partials._messages')
    <a href="{{ route('admin.v1.teams.calendars.create', $team->id) }}" class="btn btn-xs btn-info">Add holiday</a>
    <hr>
    <table class="table table-striped table-condensed">
        <thead>
        <tr>
            <th>Description</th>
            <th>Date</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @foreach($calendars as $calendar)
            <tr>
                <td>{{ $calendar->description }}</td>
                <td>{{ $calendar->date->format('d-m-Y') }}</td>
                <td><a href="{{ route('admin.v1.teams.calendars.edit', [
                    'teams' => $team->id, 'calendars' => $calendar->id
                ]) }}" class="btn btn-xs btn-warning">Edit</a></td>
                <td>
                    <form action="{{ route('admin.v1.teams.calendars.destroy', [
                        'teams' => $team->id, 'calendars' => $calendar->id
                    ]) }}" class="form-horizontal inline-block" method="post">
                        {{ csrf_field() }}
                        {{ method_field('delete') }}
                        <button class="btn btn-xs btn-danger" type="submit" data-confirm>Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $calendars->render() }}
@endsection