@extends('admin.v1.layouts.master')

@section('main')
    <div class="media">
        @if( ! $referral->attachments->isEmpty())
        <div class="media-left">
            <a href="{{ uploaded($referral->attachments->first()->uri) }}" target="_blank">
                <img class="media-object img-rounded"
                     src="{{ uploaded($referral->attachments->first()->uri) }}"
                     alt="{{ $referral->present()->name }}">
            </a>
        </div>
        @endif
        <div class="media-body">
            <h1>{{ $referral->present()->name }}</h1>
            <p>
                <strong>Email ID</strong>: {{ $referral->email }}<br>
                <strong>Phone Number</strong>: {{ $referral->phone }}<br>
                <strong>Role</strong>: {{ s2l($referral->role) }} |
                <strong>Designation</strong>: {{ $referral->designation->name }}
            </p>
        </div>
    </div>
    <br>
    @foreach($referral->settings as $name => $value)
        <strong>{{ s2l($name) }}</strong>:
        <small><em>{{ s2l($value) }}</em></small>
    @endforeach
    @include('admin.v1.layouts.partials._messages')
    <br><br>
    <a href="{{ route('admin.v1.referrals.edit', $referral->id) }}" class="btn btn-xs btn-warning">Edit</a>
{{--     <a href="{{ route('admin.v1.referrals.phone.show', $referral->id) }}" class="btn btn-xs btn-info">Transfer phone</a> --}}
    <a href="{{ route('admin.v1.referrals.team.show', $referral->id) }}" class="btn btn-xs btn-info">Assign Team</a>
    @if ( ($referral->referrals->count() <= 0) )
            <form action="{{ route('admin.v1.referrals.destroy', $referral->id) }}" class="form-inline inline-block" method="post">
                {{ csrf_field() }}
                {{ method_field('delete') }}
                <button class="btn btn-xs btn-{{$referral->trashed()? 'danger' : 'success'}}" data-status-confirm>{{$referral->trashed()? 'Enable referral' : 'Disable referral'}}</button>
            </form>
    @else
        <button type="button" class="btn btn-default btn-xs" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Cannot delete referrals with active teams." data-original-title="" title="">Disable referral
        </button>
    @endif
    <hr>

@endsection