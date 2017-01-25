@extends('admin.v1.layouts.master')

@section('main')
    <div class="media">
        <div class="media-left">
            @if( ! $project->attachments->isEmpty())
            <div class="media-left">
                <a href="{{ uploaded($project->attachments->first()->uri) }}" target="_blank">
                    <img class="media-object img-rounded"
                         src="{{ uploaded($project->attachments->first()->uri) }}"
                         alt="{{ $project->name }}">
                </a>
            </div>
            @endif
        </div>
        <div class="media-body">
            <h1>{{ $project->name }}</h1>
            <small><strong>Builder</strong>: {{ $project->builderTrashed->name }}</small>
            <br>
            <small><strong>Address</strong>: {{ $project->present()->address }}</small>
            <br>
            <small><strong>Units</strong>: {{ $project->unit_details }}</small>
            <br>
            <small><strong>Possession Date</strong>: {{ $project->present()->possessionDate }}</small>
            <br>
            <small><strong>Owner</strong>: {{ $project->owner->present()->name }}</small>
        </div>
    </div>
    @include('admin.v1.layouts.partials._messages')
    <br><br>
    @if (\Auth::user()->role === 'SUPER_ADMIN' || \Auth::user()->id === $project->owner_id )
        <a href="{{ route('admin.v1.projects.edit', $project->id) }}" class="btn btn-xs btn-info">Edit</a>
    @endif
    @if (\Auth::user()->role === 'SUPER_ADMIN' || \Auth::user()->id === $project->owner_id )
        @if($project->builderTrashed->trashed())
            <button type="button" class="btn btn-default btn-xs" data-container="body" data-toggle="popover" data-placement="bottom" data-content="{{ 'Cannot enable project with out' }}<li>active builder.</li>
            " data-original-title="" title="">Disable Project
            </button>
        @elseif($project->loan->count() <= 0)
            <form action="{{ route('admin.v1.projects.destroy', $project->id) }}" class="form-inline inline-block" method="post">
                {{ csrf_field() }}
                {{ method_field('delete') }}
                <button class="btn btn-xs btn-{{$project->trashed()? 'danger' : 'success'}}" data-status-confirm>{{$project->trashed()? 'Enable Project' : 'Disable Project'}}</button>
            </form>
        @else
            <button type="button" class="btn btn-default btn-xs" data-container="body" data-toggle="popover" data-placement="bottom" data-content="{{ 'Cannot delete project with ' }}<li>active leads.</li>
            " data-original-title="" title="">Disable Project
            </button>
        @endif
    @endif
    @if (\Auth::user()->role === 'SUPER_ADMIN' || \Auth::user()->id === $project->owner_id )
        <a href="{{ route('admin.v1.projects.queries.index', $project->id) }}" class="btn btn-xs btn-primary">LSR Queries</a>
    @endif
    <a href="{{ route('admin.v1.projectbanks.create', ['project' => $project->id]) }}" class="btn btn-xs btn-primary">Submit to bank</a>
    <hr>
    <h3>Approved by banks</h3>
    <table class="table table-condensed">
        <thead>
            <tr>
                <th>Bank Name</th>
                <th>Branch</th>
                <th>IFSC</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($project->banks as $bank)
            <tr>
                <td>{{ $bank->name }}</td>
                <td>{{ $bank->branch }}</td>
                <td>{{ $bank->ifsc_code }}</td>
                <td>
                    <span class="label label-{{ label($bank->pivot->status) }}">
                        {{ $bank->pivot->status }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
