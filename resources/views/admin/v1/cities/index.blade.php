@extends('admin.v1.layouts.master')

@section('main')
    <h1>View all cities</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Status</th>
            @if (\Auth::user()->role === 'SUPER_ADMIN')
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($cities as $city)
            <tr>
                <td>{{ $city->name }}</td>
                <td>{{ indexStatus($city) }}</td>
                @if (\Auth::user()->role === 'SUPER_ADMIN')
                    <td class="pull-right">
                        <a href="{{ route('admin.v1.cities.edit', $city->id) }}" class="btn btn-xs btn-warning">Edit</a>
                    </td>
                    <td>
                        @if ( $city->addresses->count() <= 0 )
                                <form action="{{ route('admin.v1.cities.destroy', $city->id) }}" class="form-inline inline-block" method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('delete') }}
                                    <button class="btn btn-xs btn-{{$city->trashed()? 'danger' : 'success'}}" data-status-confirm>{{$city->trashed()? 'Enable city' : 'Disable city'}}</button>
                                </form>
                        @else
                            <button type="button" class="btn btn-default btn-xs" data-container="body" data-toggle="popover" data-placement="bottom" data-content="{{ 'Cannot delete city with active user.' }}
                            {{ 'Cannot delete city with active banks.' }}
                            " data-original-title="" title="">Disable City
                            </button>
                        @endif
                    </td>
                @endif
                </tr>
        @endforeach
        </tbody>
    </table>
    {{ $cities->render() }}
@endsection
