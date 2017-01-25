@extends('admin.v1.layouts.master')

@section('main')
    <h1>View all banks</h1>
    @include('admin.v1.layouts.partials._messages')
    <table class="table table condensed table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Branch</th>
                <th>Address</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($banks as $bank)
            <tr>
                <td>{{ $bank->name }}</td>
                <td>{{ $bank->branch }}</td>
                <td>{{ $bank->present()->address }}</td>
                <td>{{ indexStatus($bank) }}</td>
                <td><a href="{{ route('admin.v1.banks.show', $bank->id) }}" class="btn btn-xs btn-info">Details</a></td>
            </tr>
            @empty
            <tr class="text-info">
                <td colspan="6">No banks</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{ $banks->render() }}
@endsection
