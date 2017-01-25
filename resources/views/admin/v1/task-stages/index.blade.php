@extends('admin.v1.layouts.master')

@section('main')
    <h1>View all task stages</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Key</th>
            <th>Status</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @foreach($stages as $stage)
            <tr>
                <td>{{ $stage->label }}</td>
                <td>{{ $stage->key }}</td>
                <td>{{ indexStatus($stage) }}</td>
                <td class="pull-right">
                    <a href="{{ route('admin.v1.task-stages.edit', $stage->id) }}" class="btn btn-xs btn-warning">Edit</a>
                </td>
                <td>
                    @if($stage->tasks->count() <= 0)
                        <form action="{{ route('admin.v1.task-stages.destroy', $stage->id) }}" class="form-inline inline-block" method="post">
                            {{ csrf_field() }}
                            {{ method_field('delete') }}
                            <button class="btn btn-xs btn-{{$stage->trashed()? 'danger' : 'success'}}" data-status-confirm>{{$stage->trashed()? 'Enable stage' : 'Disable stage'}}</button>
                        </form>
                    @else
                        <button type="button" class="btn btn-default btn-xs" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Cannot delete stage with active tasks." data-original-title="" title="">Disable stage
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $stages->render() }}
@endsection
