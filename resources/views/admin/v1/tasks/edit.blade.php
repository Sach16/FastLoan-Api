@extends('admin.v1.layouts.master')

@section('main')
    <h1>Edit task</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.tasks.update', $task->id) }}" class="form-horizontal" method="post">
        {{ csrf_field() }}
        {{ method_field('put') }}

        {{--Description--}}
        <div class="form-group col-md-9 {{ hasErrors('description', $errors) }}">
            <label class="control-label" for="inputDescription">Description</label>
            <textarea name="description" id="inputDescription" cols="30" rows="5"
                      class="form-control">{{ $task->description }}</textarea>
        </div>

        {{--From--}}
        <div class="form-group col-md-9 {{ hasErrors('from', $errors) }}">
            <label class="control-label" for="inputFromDateTimeCreate">From Date</label>
            <input class="form-control" id="inputFromDateTimeCreate" type="text"
                   name="from"
                   value="{{ $task->present()->fromInput }}">
        </div>

        {{--To--}}
        <div class="form-group col-md-9 {{ hasErrors('to', $errors) }}">
            <label class="control-label" for="inputToDateTimeCreate">To Date</label>
            <input type="hidden" value="{{ date('Y-m-d H:i:s',strtotime($task->from)) }}" id="selected-createInputDateTimeTo">
            <input class="form-control" id="inputToDateTimeCreate" type="text"
                   name="to"
                   value="{{ $task->present()->toInput }}">
        </div>

        {{--Status--}}
        <div class="form-group col-md-9 {{ hasErrors('task_status_id', $errors) }}">
            <label class="control-label" for="inputStatus">Status</label>
            <select name="task_status_id" id="inputStatus" class="form-control" data-chosen>
                @foreach($statuses as $status)
                    <option value="{{ $status->id }}" {{ isMatching($status->id, $task->status->id) }}>
                        {{ $status->label }}
                    </option>
                @endforeach
            </select>
        </div>
        {{--Stage--}}
        <div class="form-group col-md-9 {{ hasErrors('task_stage_id', $errors) }}">
            <label class="control-label" for="inputStage">Stage</label>
            <select name="task_stage_id" id="inputStage" class="form-control" data-chosen>
                @foreach($stages as $stage)
                    <option value="{{ $stage->id }}" {{ isMatching($stage->id, $task->stage->id) }}>
                        {{ $stage->label }}
                    </option>
                @endforeach
            </select>
        </div>

        {{--Priority--}}
        <div class="form-group col-md-9 {{ hasErrors('priority', $errors) }}">
            <label class="control-label" for="inputPriority">Priority</label>
            <select name="priority" id="inputPriority" class="form-control" data-chosen>
                <option value="Low" {{ isMatching($task->priority, 'Low') }}>Low</option>
                <option value="Medium" {{ isMatching($task->priority, 'Medium') }}>Medium</option>
                <option value="High" {{ isMatching($task->priority, 'High') }}>High</option>
            </select>
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <a href="{{ route('admin.v1.tasks.show', $task->id) }}" class="btn btn-default">Back</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection
