@extends('admin.v1.layouts.master')

@section('main')
    <h1>View all builders</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Address</th>
            <th>Status</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @foreach($builders as $builder)
        <tr>
            <td>{{ $builder->name }}</td>
            <td>{{ $builder->addresses->first()->present()->address }}</td>
            <td>{{ indexStatus($builder) }}</td>
            <td>
                <a href="{{ route('admin.v1.builders.show', $builder->id) }}" class="btn btn-xs btn-info">Details</a>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
@endsection