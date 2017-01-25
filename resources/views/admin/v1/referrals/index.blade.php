@extends('admin.v1.layouts.master')

@section('main')
    <h1>View all Referrals</h1>
    @include('admin.v1.layouts.partials._messages')
    <table class="table table condensed table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email ID</th>
                <th>Phone Number</th>
                <th>Designation</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($referrals as $referral)
            <tr>
                <td>{{ $referral->present()->name }}</td>
                <td>{{ $referral->email }}</td>
                <td>{{ $referral->phone }}</td>
                <td>{{ $referral->designation->name }}</td>
                <td>{{ indexStatus($referral) }}</td>
                <td>
                    <a href="{{ route('admin.v1.referrals.show', $referral->id) }}" class="btn btn-xs btn-info">Details</a>
                    <a href="{{ route('admin.v1.referrals.edit', $referral->id) }}" class="btn btn-xs btn-warning">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $referrals->render() }}
@endsection