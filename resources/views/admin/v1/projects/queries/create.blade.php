@extends('admin.v1.layouts.master')

@section('main')
    <h1>Add new LSR Query</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.projects.queries.store', $project->id) }}"
          method="post" class="form-horizontal">
        {{ csrf_field() }}

        {{--Query--}}
        <div class="form-group col-md-8 {{ hasErrors('query', $errors) }}">
            <label class="control-label" for="inputQuery">Query</label>
            <input type="text" class="form-control" id="inputQuery" name="query" value="{{ old('query') }}">
        </div>

        {{--Start Date--}}
        <div class="form-group col-md-8 {{ hasErrors('start_date', $errors) }}">
            <label class="control-label" for="inputStartDate">Start Date</label>
            <input type="text" class="form-control" id="inputStartDate" name="start_date" data-date-time
                   value="{{ old('start_date') }}">
        </div>

        {{--End Date--}}
        <div class="form-group col-md-8 {{ hasErrors('end_date', $errors) }}">
            <label class="control-label" for="inputEndDate">End Date</label>
            <input type="text" class="form-control" id="inputEndDate" name="end_date" data-date-time
                   value="{{ old('end_date') }}">
        </div>

        {{--Due Date--}}
{{--         <div class="form-group col-md-8 {{ hasErrors('due_date', $errors) }}">
            <label class="control-label" for="inputDueDate">Due Date</label>
            <input type="text" class="form-control" id="inputDueDate" name="due_date" data-date
                   value="{{ old('due_date') }}">
        </div> --}}

        {{--Due Date--}}
        <div class="form-group col-md-8 {{ hasErrors('status', $errors) }}">
            <label class="control-label" for="inputStatus">Status</label>
            <select name="status" id="inputStatus" class="form-control">
                <option value="-1" disabled selected >Slelect status</option>
                <option value="APPROVED" {{ isMatching(old('status'), 'APPROVED')}}>Approved</option>
                <option value="PENDING" {{ isMatching(old('status'), 'PENDING')}}>Pending</option>
                <option value="SUBMITTED" {{ isMatching(old('status'), 'SUBMITTED')}}>Submitted</option>
                <option value="REJECTED" {{ isMatching(old('status'), 'REJECTED')}}>Rejected</option>
            </select>
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-md-10 col-md-offset-2">
                <button type="reset" class="btn btn-default">Reset</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection
