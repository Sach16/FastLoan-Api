@extends('admin.v1.layouts.master')

@section('main')
    <h1>Logins</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Role</th>
            <th>Time</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse($logins as $login)
            <tr>
                <td>{{ $login->user->present()->name}}</td>
                <td>{{ $login->user->role }}</td>
                <td>{{ $login->created_at->format('d-m-Y H:i:s') }}</td>
                <td>{{ $login->action }}</td>
            </tr>
        @empty
            <tr class="text-info">
                <td colspan="6">No logins</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    {{ $logins->render() }}
@endsection
