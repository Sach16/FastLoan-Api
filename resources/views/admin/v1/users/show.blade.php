@extends('admin.v1.layouts.master')

@section('main')
    <div class="media">
    <?php
    $PROFILE_PICTURE= $user->attachments->filter(function ($item) {
        return ($item->type == 'PROFILE_PICTURE'?true:false);
    });
    $ADDRESS_PROOF= $user->attachments->filter(function ($item) {
        return ($item->type == 'ADDRESS_PROOF'?true:false);
    });
    $ID_PROOF= $user->attachments->filter(function ($item) {
        return ($item->type == 'ID_PROOF'?true:false);
    });
    $EXPERIENCE_DOCUMENT = $user->attachments->filter(function ($item) {
        return ($item->type == 'EXPERIENCE_DOCUMENT'?true:false);
    });
    $PRODUCT_DOCUMENT = $user->attachments->filter(function ($item) {
        return ($item->type == 'PRODUCT_DOCUMENT'?true:false);
    });
    ?>
        @if( ! $PROFILE_PICTURE->isEmpty())
        <div class="media-left">
            <a href="{{ uploaded($PROFILE_PICTURE->first()->uri) }}" target="_blank">
                <img class="media-object img-rounded"
                     src="{{ uploaded($PROFILE_PICTURE->first()->uri) }}"
                     alt="{{ $user->present()->name }}">
            </a>
        </div>
        @endif
        <div class="media-body">
            <h1>{{ $user->present()->name }}</h1>
            <p>
                <strong>Email ID</strong>: {{ $user->email }}<br>
                <strong>Phone Number</strong>: {{ $user->phone }}<br>
                <strong>Role</strong>: {{ s2l($user->role) }} |
                <strong>Designation</strong>: {{ $user->designationTrashed->name }}
            </p>
        </div>
    </div>
    <br>
    @foreach($user->settings as $name => $value)
        <strong>{{ s2l($name) }}</strong>:
        <small><em>{{ s2l($value) }}</em></small>
    @endforeach

    @include('admin.v1.layouts.partials._messages')
    <br><br>
    <a href="{{ route('admin.v1.users.edit', $user->id) }}" class="btn btn-xs btn-warning">Edit</a>
    <a href="{{ route('admin.v1.users.phone.show', $user->id) }}" class="btn btn-xs btn-info">Transfer phone</a>
    @if($user->designationTrashed->trashed())
        <button type="button" class="btn btn-default btn-xs" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Cannot enable user with out <li>active designation.</li>
        " data-original-title="" title="">Disable User
        </button>
    @elseif( $user->teams->count() <=0 && $user->banks->count() <=0 && $user->tasks->count() <=0 && $user->projects->count() <=0 && $user->assignee->count() <=0 )
        <form action="{{ route('admin.v1.users.destroy', $user->id) }}" class="form-inline inline-block" method="post">
            {{ csrf_field() }}
            {{ method_field('delete') }}
            <button class="btn btn-xs btn-{{$user->trashed()? 'danger' : 'success'}}" data-status-confirm>{{$user->trashed()? 'Enable User' : 'Disable User'}}</button>
        </form>
    @else
        <button type="button" class="btn btn-default btn-xs" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Cannot delete user with{{ $user->teams->count() > 0 ? '<li>active teams.</li>':'' }}
        {{ $user->banks->count() > 0 ? '<li> active banks.</li>':'' }}
        {{ $user->tasks->count() > 0 ? '<li> active tasks.</li>':'' }}
        {{ $user->projects->count() > 0 ? '<li> active projects.</li>':'' }}
        {{ $user->assignee->count() > 0 ? '<li> active leads.</li>':'' }}
        " data-original-title="" title="">Disable User
        </button>
    @endif
    <hr>
     <div class="row col-md-12">
        @if( ! $ADDRESS_PROOF->isEmpty())
            <div class="media">
                <div class="media-left">
                    <a href="{{ $ADDRESS_PROOF->first()->uri }}" target="_blank">
                        <i class="glyphicon glyphicon-file"></i>
                    </a>
                </div>
                <div class="media-body">
                    <h4 class="media-heading">Address Proof</h4>
                    <a href="{{ uploaded($ADDRESS_PROOF->first()->uri) }}" target="_blank" class="btn btn-xs btn-info">View</a>
                </div>
            </div>
        @endif
        @if(! $ID_PROOF->isEmpty() || ! $EXPERIENCE_DOCUMENT->isEmpty() || ! $PRODUCT_DOCUMENT->isEmpty())
        @if( ! $ID_PROOF->isEmpty())
            <div class="media">
                <div class="media-left">
                    <a href="{{ $ID_PROOF->first()->uri }}" target="_blank">
                        <i class="glyphicon glyphicon-file"></i>
                    </a>
                </div>
                <div class="media-body">
                    <h4 class="media-heading">ID Proof</h4>
                    <a href="{{ uploaded($ID_PROOF->first()->uri) }}" target="_blank" class="btn btn-xs btn-info">View</a>
                </div>
            </div>
        @endif
        @if( ! $EXPERIENCE_DOCUMENT->isEmpty())
            <div class="media">
                <div class="media-left">
                    <a href="{{ $EXPERIENCE_DOCUMENT->first()->uri }}" target="_blank">
                        <i class="glyphicon glyphicon-file"></i>
                    </a>
                </div>
                <div class="media-body">
                    <h4 class="media-heading">Experience with Bank</h4>
                    <a href="{{ uploaded($EXPERIENCE_DOCUMENT->first()->uri) }}" target="_blank" class="btn btn-xs btn-info">View</a>
                </div>
            </div>
        @endif
        @if( ! $PRODUCT_DOCUMENT->isEmpty())
            <div class="media">
                <div class="media-left">
                    <a href="{{ $PRODUCT_DOCUMENT->first()->uri }}" target="_blank">
                        <i class="glyphicon glyphicon-file"></i>
                    </a>
                </div>
                <div class="media-body">
                    <h4 class="media-heading">Products handled</h4>
                    <a href="{{ uploaded($PRODUCT_DOCUMENT->first()->uri) }}" target="_blank" class="btn btn-xs btn-info">View</a>
                </div>
            </div>
        @endif
        <hr>
        @endif
    </div>
    <h3>Teams</h3>
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @foreach($user->teams as $team)
            <tr>
                <td>{{ $team->name }}</td>
                <td>{{ $team->description }}</td>
                <td class="pull-right">
                    <a href="{{ route('admin.v1.teams.show', $team->id) }}" class="btn btn-xs btn-info">Details</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
