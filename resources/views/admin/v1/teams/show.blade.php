@extends('admin.v1.layouts.master')

@section('main')
    <h1>{{ $team->name }}</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <p>{{ $team->description }}</p>
    <br>
    @if(!$team->trashed())
        <a href="{{ route('admin.v1.teams.edit', $team->id) }}" class="btn btn-xs btn-warning">Edit team</a>
    @endif
    <a href="{{ route('admin.v1.teams.calendars.index', $team->id) }}" class="btn btn-xs btn-info">Holiday List</a>
    <a href="{{ route('admin.v1.teams.attendances.index', $team->id) }}" class="btn btn-xs btn-info">Attendance History</a>
    @if($team->members->count()==0 && !$team->trashed())
        <form action="{{ route('admin.v1.teams.destroy', $team->id) }}" class="form-horizontal inline-block" method="post">
            {{ csrf_field() }}
            {{ method_field('delete') }}
            <button class="btn btn-xs btn-danger" type="submit" data-confirm>Disable team</button>
        </form>
    @elseif($team->members->count() > 0 && !$team->trashed())
        <button type="button" class="btn btn-default btn-xs" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Cannot delete team with<li>active team member(s).</li>" data-original-title="" title="">Disable team
        </button>
    @else
        <form action="{{ route('admin.v1.teams.destroy', $team->id) }}" class="form-horizontal inline-block" method="post">
            {{ csrf_field() }}
            {{ method_field('delete') }}
            <button class="btn btn-xs btn-danger" type="submit" data-confirm>Enable team</button>
        </form>
    @endif
    <h3>Members</h3>
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email ID</th>
            <th>Phone Number</th>
            <th>Owner</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <?php

        $only_members = $team->members->filter(function ($item) {
            if (!$item->pivot->is_owner) {
                return $item;
            }
        });

        ?>
        @foreach($team->members as $member)
            <tr>
                <td>{{ $member->present()->name }}</td>
                <td>{{ $member->email }}</td>
                <td>{{ $member->phone }}</td>
                <td>{{ $member->pivot->is_owner ? 'YES' : 'NO' }}</td>
                <td><a href="{{ route('admin.v1.teams.edit.settings', [$team->id,$member->id]) }}"
                       class="btn btn-xs btn-warning">Edit target setting</a>
                </td>
                <td>
                    @if(!$member->pivot->is_owner)
                         <a href="{{ route('admin.v1.teams.remove.from.team', [$team->id,$member->id]) }}"
                       class="btn btn-xs btn-danger">Remove from the team</a>
                    @endif

                    @if(\Auth::user()->role === 'SUPER_ADMIN' && $only_members->count()==0 )
                        <a href="{{ route('admin.v1.teams.remove.owner.from.team', [$team->id,$member->id]) }}"
                       class="btn btn-xs btn-danger">Remove owner from the team</a>
                   @endif

                   @if(\Auth::user()->role === 'SUPER_ADMIN' && !$member->pivot->is_owner)
                        <a href="{{ route('admin.v1.teams.multi.owner.for.team', [$team->id,$member->id]) }}"
                       class="btn btn-xs btn-danger">Add as Owner</a>
                   @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection