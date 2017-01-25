@extends('admin.v1.layouts.master')

@section('main')
    <h1>View all teams</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th>Team Name</th>
            <th>Description</th>
            <th>Bank Code</th>
            <th>Status</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @foreach($teams as $team)
            <tr>
                <td>{{ $team->name }}</td>
                <td>{{ $team->description }}</td>
                <td>{{ $team->bank_code }}</td>
                <td>{{ indexStatus($team) }}</td>
                <td><a href="{{ route('admin.v1.teams.show', $team->id) }}" class="btn btn-xs btn-primary">Details</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $teams->render() }}
@endsection