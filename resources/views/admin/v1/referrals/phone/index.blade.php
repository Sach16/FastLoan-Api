@extends('admin.v1.layouts.master')

@section('main')
    <h1>View all Referrals</h1>
    @include('admin.v1.layouts.partials._messages')
    <table class="table table condensed table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Branch</th>
                <th>Address</th>
                <th>Designation</th>
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
                <td>
                    <a href="{{ route('admin.v1.referrals.show', $referral->id) }}" class="btn btn-xs btn-info">Details</a>
                    <a href="{{ route('admin.v1.referrals.edit', $referral->id) }}" class="btn btn-xs btn-warning">Edit</a>
                    <form action="{{ route('admin.v1.referrals.destroy', $referral->id) }}" method="post"
                          class="form-horizontal inline-block">
                        {{ csrf_field() }}
                        {{ method_field('delete') }}
                        <button class="btn btn-xs btn-danger" type="submit" data-confirm>Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $referrals->render() }}
@endsection