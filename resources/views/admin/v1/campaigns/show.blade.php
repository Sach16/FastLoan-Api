@extends('admin.v1.layouts.master')

@section('main')
    <h1>{{ $campaign->name }}</h1>
    @include('admin.v1.layouts.partials._messages')
    <strong>Organized by</strong>: {{ $campaign->organizer }}
    <br>
    <strong>Description</strong>: {{ $campaign->description }}
    <br>
    <strong>Promotional Material</strong>: {{ $campaign->promotionals }}
    <br>
    {{--Every campaign should have an address--}}
    <strong>Venue</strong>: {{ $campaign->addresses->first()->present()->address }}
    <br>
    <strong>From Date</strong>: {{ $campaign->present()->fromDate }} |
    <strong>To Date</strong>: {{ $campaign->present()->toDate }}
    <br><br>
    <a href="{{ route('admin.v1.campaigns.edit', $campaign->id) }}" class="btn btn-xs btn-warning">Edit</a>
    <form action="{{ route('admin.v1.campaigns.destroy', $campaign->id) }}" class="form-inline inline-block" method="post">
        {{ csrf_field() }}
        {{ method_field('delete') }}
        <button class="btn btn-xs btn-{{$campaign->trashed()? 'danger' : 'success'}}" data-status-confirm>{{$campaign->trashed()? 'Enable campaign' : 'Disable campaign'}}</button>
    </form>
    <hr>
    <h3>Team Members</h3>
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th>Team Member</th>
            <th>Email ID</th>
            <th>Phone Number</th>
        </tr>
        </thead>
        <tbody>
            @foreach($campaign->members as $member)
            <tr>
                <td>{{ $member->present()->name }}</td>
                <td>{{ $member->email }}</td>
                <td>{{ $member->phone }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
