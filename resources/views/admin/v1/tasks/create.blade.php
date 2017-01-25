@extends('admin.v1.layouts.master')

@section('main')
    <h1>Add  new task</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form
            enctype="multipart/form-data"
            action="{{ route('admin.v1.tasks.store') }}"
            class="form-horizontal"
            method="post" >
        {{ csrf_field() }}
        <input type="hidden" name="uuid" value="{{ uuid() }}">

        {{--Loan--}}
        <div class="form-group col-md-9 {{ hasErrors('loan_id', $errors) }}">
            <label class="control-label" for="inputLoan">Loan</label>
            <input type="hidden" value="{{ old('loan_id') }}" id="selected-loan_id">
            <select name="loan_id" id="inputLoan" class="form-control"></select>
        </div>

        {{--Description--}}
        <div class="form-group col-md-9 {{ hasErrors('description', $errors) }}">
            <label class="control-label" for="inputDescription">Description</label>
            <textarea name="description" id="inputDescription" cols="30" rows="5"
                      class="form-control">{{ old('description') }}</textarea>
        </div>

        {{--From--}}
        <div class="form-group col-md-9 {{ hasErrors('from', $errors) }}">
            <label class="control-label" for="inputFromDateTimeCreate">From Date</label>
            <input class="form-control" id="inputFromDateTimeCreate" type="text"
                   name="from"
                   value="{{ old('from') }}">
        </div>

        {{--To--}}
        <div class="form-group col-md-9 {{ hasErrors('to', $errors) }}">
            <label class="control-label" for="inputToDateTimeCreate">To Date</label>
            <input class="form-control" id="inputToDateTimeCreate" type="text"
                   name="to"
                   value="{{ old('to') }}">
        </div>

        {{--Status--}}
        <div class="form-group col-md-9 {{ hasErrors('task_status_id', $errors) }}">
            <label class="control-label" for="inputStatus">Status</label>
            <input type="hidden" value="{{ old('task_status_id') }}" id="selected-status">
            <select name="task_status_id" id="inputStatus" class="form-control" data-chosen>
                <option value="-1" disabled>Select task status</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->id }}" selected >
                        {{ $status->label }}
                    </option>
                @endforeach
            </select>
        </div>

        {{--Stage--}}
        <div class="form-group col-md-9 {{ hasErrors('task_stage_id', $errors) }}">
            <label class="control-label" for="inputStage">Stage</label>
            <input type="hidden" value="{{ old('task_stage_id') }}" id="selected-stage">
            <select name="task_stage_id" id="inputStage" class="form-control" data-chosen>
                <option value="-1" disabled selected>Select task stage</option>
                @foreach($stages as $stage)
                    <option value="{{ $stage->id }}" {{ isMatching($stage->id, old('task_stage_id'))}}>
                        {{ $stage->label }}
                    </option>
                @endforeach
            </select>
        </div>

        {{--Priority--}}
        <div class="form-group col-md-9 {{ hasErrors('priority', $errors) }}">
            <label class="control-label" for="inputPriority">Priority</label>
            <input type="hidden" value="{{ old('priority') }}" id="selected-priority">
            <select name="priority" id="inputPriority" class="form-control" data-chosen>
                <option value="-1" disabled selected>Select priority</option>
                <option value="Low" {{ isMatching(old('priority'), 'Low')}}>Low</option>
                <option value="Medium" {{ isMatching(old('priority'), 'Medium')}}>Medium</option>
                <option value="High" {{ isMatching(old('priority'), 'High')}}>High</option>
            </select>
        </div>

        {{--Document Name--}}
        <div class="form-group col-md-9 {{ hasErrors('name', $errors) }}">
            <label class="control-label" for="inputName">Document Name</label>
            <input class="form-control" id="inputName" type="text"
                   name="name"
                   value="{{ old('name') }}">
        </div>

        {{--Attachment--}}
        <div class="form-group col-md-9 {{ hasErrors('document', $errors) }}">
            <label class="control-label" for="inputDocument">Document</label>
            <input class="form-control" id="inputDocument" type="file"
                   name="document"
                   value="{{ old('document') }}">
            <span class="help-block">
                <small>
                    <em>Only PDF and Image files. Maximum file size: 10MB</em>
                </small>
            </span>
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <button class="btn btn-default" type="reset">Reset</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection
