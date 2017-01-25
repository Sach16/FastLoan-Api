@extends('admin.v1.layouts.master')

@section('main')
    <h1>Task Details</h1>
    @include('admin.v1.layouts.partials._messages')
    <a href="{{ route('admin.v1.tasks.edit', $task->id) }}" class="btn btn-xs btn-warning">Edit</a>
    @if( !$task->statusTrashed->trashed() && !$task->stageTrashed->trashed() )
    <form action="{{ route('admin.v1.tasks.destroy', $task->id) }}" class="form-inline inline-block" method="post">
        {{ csrf_field() }}
        {{ method_field('delete') }}
        <button class="btn btn-xs btn-{{$task->trashed()? 'danger' : 'success'}}" data-status-confirm>{{$task->trashed()? 'Enable Task' : 'Disable Task'}}</button>
    </form>
    @else
        <button type="button" class="btn btn-default btn-xs" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Cannot Enable Task with out 
        {{  $task->statusTrashed->trashed() ? '<li>active status.</li>': ''}} 
        {{  $task->stageTrashed->trashed() ? '<li>active stage.</li>': ''}}
        " data-original-title="" title="">Enable Task
        </button>
    @endif
    <hr>
    <strong>Description</strong>:
    {{ $task->description }}
    <br>
    <strong>From</strong>: {{ $task->present()->fromDate }} |
    <strong>To</strong>: {{ $task->present()->toDate }}
    <br>
    <strong>Owner</strong>: {{ $task->user ? $task->user->present()->name:'' }}
    <br>
    <strong>Priority</strong>: {{ $task->priority }} |
    <strong>Status</strong>: {{ $task->statusTrashed->label }} |
    <strong>Stage</strong>: {{ $task->stageTrashed->label }}
    <hr>
    <h3>Task Documents</h3>
    <div class="row col-md-12">
      <a href="{{ route('admin.v1.tasks.documents.create', $task->id) }}" class="btn btn-xs btn-primary">Add new document</a>
    </div>
    @if (!$documents->attachments->isEmpty())
    <br>
    <div class="row col-md-12">
        @foreach($documents->attachments as $attachment)
            <div class="media">
                <div class="media-left">
                    <a href="{{ uploaded($attachment->uri) }}" target="_blank">
                        <i class="glyphicon glyphicon-file"></i>
                    </a>
                </div>
                <div class="media-body">
                    <h4 class="media-heading">{{ $attachment->name }}</h4>
                    {{--Escaped when entering to database--}}
                    <p>{!! nl2br($attachment->description) !!}</p>
                    <a href="{{ uploaded($attachment->uri) }}" target="_blank" class="btn btn-xs btn-info">View</a>



                    <a href="{{ route('admin.v1.tasks.documents.edit', [
                                'banks' => $task->id, 'documents' => $attachment->id
                            ]) }}"
                       class="btn btn-xs btn-warning">Edit</a>

                    <form
                            action="{{ route('admin.v1.tasks.documents.destroy',
                                ['banks' => $task->id, 'documents' => $attachment->id]) }}"
                            class="form-horizontal inline-block" method="post"
                    >
                        {{ csrf_field() }}
                        {{ method_field('delete') }}
                        <button type="submit" class="btn btn-xs btn-danger" data-confirm>Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
        <hr>
    </div>
    @endif
@endsection
