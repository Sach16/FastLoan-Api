@extends('admin.v1.layouts.master')

@section('main')
    <h1>View all projects</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <table class="table table condensed table-striped">
        <thead>
        <tr>
            <th>Project Name</th>
            <th>Builder Name</th>
            <th>Owner</th>
            <th>Units</th>
            <th>Possession Date</th>
            <th>Status</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($projects as $project)
            <tr>
                <td>{{ $project->name }}</td>
                <td>{{ $project->builderTrashed->name }}</td>
                <td>{{ $project->owner->present()->name }}</td>
                <td>{{ $project->unit_details }}</td>
                <td>{{ $project->possession_date->format('d-m-Y') }}</td>
                <td>{{ indexStatus($project) }}</td>
                <td>
                    <a href="{{ route('admin.v1.projects.show', $project->id) }}" class="btn btn-xs btn-info">
                        Details
                    </a>
                </td>
            </tr>
        @empty
            <tr class="text-info">
                <td colspan="6">No projects</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    {{ $projects->render() }}
@endsection
