@extends('admin.v1.layouts.master')

@section('main')
    <h1>View all customers</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email ID</th>
            <th>Phone Number</th>
            <th>Status</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @foreach($customers as $customer)
        <tr>
            <td>{{ $customer->present()->name }}</td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->phone }}</td>
            <td>{{ indexStatus($customer) }}</td>
            <td>
                <a href="{{ route('admin.v1.customers.show', $customer->id) }}" class="btn btn-xs btn-info">Details</a>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    {{ $customers->render() }}
@endsection