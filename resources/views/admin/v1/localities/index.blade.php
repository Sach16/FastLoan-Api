@extends('admin.v1.layouts.master')

@section('main')
    <h1>View all Localities</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>State</th>
            <th>Status</th>
            @if (\Auth::user()->role === 'SUPER_ADMIN')
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @forelse($localities as $locality)
            <tr>
                <td>{{ $locality->name }}</td>
                <td>{{ $locality->description }}</td>
                <td>{{ $locality->state->name }}</td>
                <td>{{ indexStatus($locality) }}</td>
                @if (\Auth::user()->role === 'SUPER_ADMIN')
                    <td><a href="{{ route('admin.v1.localities.edit', $locality->id) }}"
                           class="btn btn-xs btn-warning">Edit</a></td>
                    <td>
                    @if( !$locality->state->trashed() )
                        <form action="{{ route('admin.v1.localities.destroy', $locality->id) }}" class="form-inline inline-block" method="post">
                            {{ csrf_field() }}
                            {{ method_field('delete') }}
                            <button class="btn btn-xs btn-{{$locality->trashed()? 'danger' : 'success'}}" data-status-confirm>{{$locality->trashed()? 'Enable locality' : 'Disable locality'}}</button>
                        </form>
                    @else
                        <button type="button" class="btn btn-default btn-xs" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Cannot enable locality with out <li> active state.</li>" data-original-title="" title="">Enable locality
                        </button>
                    @endif
                    </td>
                @endif
            </tr>
        @empty
            <tr class="text-info">
                <td colspan="6">No localities</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    {{ $localities->render() }}
@endsection
