@extends('admin.v1.layouts.master')

@section('main')
    <h1>LSR Queries <small>({{ $project->name }})</small></h1>
    @include('admin.v1.layouts.partials._messages')
    <br>
    <a href="{{ route('admin.v1.projects.queries.create', $project->id) }}" class="btn btn-xs btn-info">Add LSR Query</a>
    <hr>
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th>Query</th>
            <th>Agent</th>
            <th>Start Date</th>
            <th>End Date</th>
            {{-- <th>Due Date</th> --}}
            <th>Status</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @foreach($project->queries as $query)
        <tr>
            <td>{{ $query->query }}</td>
            <td>{{ $query->assignee->present()->name }}</td>
            <td>{{ $query->present()->startDate }}</td>
            <td>{{ $query->present()->endDate }}</td>
            {{-- <td>{{ $query->present()->dueDate }}</td> --}}
            <td><span class="label label-{{ strtolower($query->status) }}">{{ $query->status }}</span></td>
            <td>
                <a href="{{ route('admin.v1.projects.queries.edit', [
                    'projects' => $project->id, 'queries' => $query->id
                ]) }}"
                   class="btn btn-xs btn-primary">Edit</a>
            </td>
            <td>
                <form action="{{ route('admin.v1.projects.queries.destroy', [
                    'projects' => $project->id, 'queries' => $query->id
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
@endsection
