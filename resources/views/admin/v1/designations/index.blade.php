@extends('admin.v1.layouts.master')

@section('main')
    <h1>View all designations</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Status</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @forelse($designations as $designation)
            <tr>
                <td>{{ $designation->name }}</td>
                <td>{{ $designation->description }}</td>
                <td>{{ indexStatus($designation) }}</td>
                <td><a href="{{ route('admin.v1.designations.edit', $designation->id) }}"
                       class="btn btn-xs btn-warning">Edit</a></td>
                <td>
                @if($designation->user->count() <= 0)
                    <form action="{{ route('admin.v1.designations.destroy', $designation->id) }}" class="form-inline inline-block" method="post">
                        {{ csrf_field() }}
                        {{ method_field('delete') }}
                        <button class="btn btn-xs btn-{{$designation->trashed()? 'danger' : 'success'}}" data-status-confirm>{{$designation->trashed()? 'Enable designation' : 'Disable designation'}}</button>
                    </form>
                @else
                    <button type="button" class="btn btn-default btn-xs" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Cannot delete designations with active users." data-original-title="" title="">Disable designation
                    </button>
                @endif
                </td>
            </tr>
        @empty
            <tr class="text-info">
                <td colspan="6">No designations</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    {{ $designations->render() }}
@endsection
