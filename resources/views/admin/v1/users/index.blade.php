@extends('admin.v1.layouts.master')

@section('main')
    <h1>View all users</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email ID</th>
            <th>Phone Number</th>
            <th>Designation</th>
            <th>Role</th>
            <th>Status</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->present()->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->designation->name }}</td>
                <td>{{ s2l($user->role) }}</td>
                <td>{{ indexStatus($user) }}</td>
                <td>
                    <a href="{{ route('admin.v1.users.show', $user->id) }}" class="btn btn-xs btn-info">Details</a>
                    <a href="{{ route('admin.v1.users.edit', $user->id) }}" class="btn btn-xs btn-warning">Edit</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $users->render() }}
@endsection