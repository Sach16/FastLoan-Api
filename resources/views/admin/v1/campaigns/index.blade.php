@extends('admin.v1.layouts.master')

@section('main')
    <h1>View all campaigns</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <table class="table table-condensed">
        <thead>
        <tr>
            <th>Name</th>
            <th>Organized By</th>
            <th>Type</th>
            <th>From Date</th>
            <th>To Date</th>
            <th>Status</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @forelse($campaigns as $campaign)
        <tr>
            <td>{{ $campaign->name }}</td>
            <td>{{ $campaign->organizer }}</td>
            <td>{{ $campaign->present()->ctype }}</td>
            <td>{{ $campaign->present()->fromDate }}</td>
            <td>{{ $campaign->present()->toDate }}</td>
            <td>{{ indexStatus($campaign) }}</td>
            <td>
                <a href="{{ route('admin.v1.campaigns.show', $campaign->id) }}"
                   class="btn btn-xs btn-info">Details
                </a>
            </td>
        </tr>
        @empty
            <tr class="text-info">
                <td colspan="6">No campaigns</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    {{ $campaigns->render() }}
@endsection
