@extends('admin.v1.layouts.master')

@section('main')
    <h1>View all task statuses</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <table class="table table-striped table-condensed">
        <thead>
        <tr>
            <th>Label</th>
            <th>Key</th>
            <th>Status</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @foreach($statuses as $status)
            <tr>
                <td>{{ $status->label }}</td>
                <td>{{ $status->key }}</td>
                <td>{{ indexStatus($status) }}</td>
                <td class="pull-right">
                    <a href="{{ route('admin.v1.task-statuses.edit', $status->id) }}"
                       class="btn btn-xs btn-warning">Edit</a>
                </td>
                <td>
                    @if($status->tasks->count() <= 0)
                        <form action="{{ route('admin.v1.task-statuses.destroy', $status->id) }}" class="form-inline inline-block" method="post">
                            {{ csrf_field() }}
                            {{ method_field('delete') }}
                            <button class="btn btn-xs btn-{{$status->trashed()? 'danger' : 'success'}}" data-status-confirm>{{$status->trashed()? 'Enable status' : 'Disable status'}}</button>
                        </form>
                    @else
                        <button type="button" class="btn btn-default btn-xs" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Cannot delete status with active tasks." data-original-title="" title="">Disable status
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection