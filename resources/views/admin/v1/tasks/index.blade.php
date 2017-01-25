@extends('admin.v1.layouts.master')

@section('main')
    <h1>View all tasks</h1>
    @include('admin.v1.layouts.partials._messages')
    @if(\Auth::user()->role === 'DSA_OWNER')
        <a href="{{ route('admin.v1.tasks-transfer.index') }}" class="btn btn-xs btn-warning">Transfer ownership</a>
    @endif
    <hr>
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th>Type</th>
            <th>Stage</th>
            <th>Owner</th>
            <th>From</th>
            <th>To</th>
            <th>Priority</th>
            <th>Task Status</th>
            <th>Status</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @forelse($tasks as $task)
        <tr>
            <td>{{ @$task->taskable->type->name }}</td>
            <td>{{ $task->stage->label }}</td>
            @if($task->user)
            <td>{{ $task->user->present()->name }}</td>
            @else
            <td></td>
            @endif
            <td>{{ $task->present()->fromDate }}</td>
            <td>{{ $task->present()->toDate }}</td>
            <td>{{ $task->priority }}</td>
            <td>{{ $task->status->label }}</td>
            <td>{{ indexStatus($task) }}</td>
            <td><a href="{{ route('admin.v1.tasks.show', $task->id) }}" class="btn btn-info btn-xs">Details</a></td>
        </tr>
        @empty
        <tr class="default">
            <td colspan="8">No tasks here.</td>
        </tr>
        @endforelse
        </tbody>
    </table>
    {{ $tasks->render() }}
@endsection