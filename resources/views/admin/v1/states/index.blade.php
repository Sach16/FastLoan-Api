@extends('admin.v1.layouts.master')

@section('main')
    <h1>View all states</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th>State Name</th>
            <th>Description</th>
            <th>Status</th>
            @if (\Auth::user()->role === 'SUPER_ADMIN')
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @forelse($states as $state)
            <tr>
                <td>{{ $state->name }}</td>
                <td>{{ $state->description }}</td>
                <td>{{ indexStatus($state) }}</td>
                @if (\Auth::user()->role === 'SUPER_ADMIN')
                    <td><a href="{{ route('admin.v1.states.edit', $state->id) }}"
                           class="btn btn-xs btn-warning">Edit</a></td>
                    <td>
                        @if($state->localities->isEmpty())
                            <form action="{{ route('admin.v1.states.destroy', $state->id) }}" class="form-inline inline-block" method="post">
                                {{ csrf_field() }}
                                {{ method_field('delete') }}
                                <button class="btn btn-xs btn-{{$state->trashed()? 'danger' : 'success'}}" data-status-confirm>{{$state->trashed()? 'Enable state' : 'Disable state'}}</button>
                            </form>
                        @else
                            <button type="button" class="btn btn-default btn-xs" data-container="body" data-toggle="popover" data-placement="bottom" data-content="{{ 'Cannot delete state with active localities.' }}
                            " data-original-title="" title="">Disable state
                            </button>
                        @endif
                    </td>
                @endif
            </tr>
        @empty
            <tr class="text-info">
                <td colspan="6">No states</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    {{ $states->render() }}
@endsection
