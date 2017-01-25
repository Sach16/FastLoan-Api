@extends('admin.v1.layouts.master')

@section('main')
    <h1>Edit LSR query</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.projects.queries.update', [
        'projects' => $project->id, 'queries' => $query->id
    ]) }}" method="post" class="form-horizontal">
        {{ csrf_field() }}
        {{ method_field('put') }}

        {{--Query--}}
        <div class="form-group col-md-8 {{ hasErrors('query', $errors) }}">
            <label class="control-label" for="inputQuery">Query</label>
            <input type="text" class="form-control" id="inputQuery" name="query" value="{{ $query->query }}">
        </div>

        {{--Start Date--}}
        <div class="form-group col-md-8 {{ hasErrors('start_date', $errors) }}">
            <label class="control-label" for="inputStartDate">Start Date</label>
            <input type="text" class="form-control" id="inputStartDate" name="start_date" data-date-time
                   value="{{ $query->present()->startDateInput }}">
        </div>

        {{--End Date--}}
        <div class="form-group col-md-8 {{ hasErrors('end_date', $errors) }}">
            <label class="control-label" for="inputEndDate">End Date</label>
            <input type="text" class="form-control" id="inputEndDate" name="end_date" data-date-time
                   value="{{ $query->present()->endDateInput }}">
        </div>

        {{--Due Date--}}
{{--         <div class="form-group col-md-8 {{ hasErrors('due_date', $errors) }}">
            <label class="control-label" for="inputDueDate">Due Date</label>
            <input type="text" class="form-control" id="inputDueDate" name="due_date" data-date
                   value="{{ $query->present()->dueDateInput }}">
        </div>
 --}}
        {{--Due Date--}}
        <div class="form-group col-md-8 {{ hasErrors('due_date', $errors) }}">
            <label class="control-label" for="inputStatus">Status</label>
            <select name="status" id="inputStatus" class="form-control">
                <option value="APPROVED" {{ isMatching($query->status, 'APPROVED')}}>Approved</option>
                <option value="PENDING" {{ isMatching($query->status, 'PENDING')}}>Pending</option>
                <option value="SUBMITTED" {{ isMatching($query->status, 'SUBMITTED')}}>Submitted</option>
                <option value="REJECTED" {{ isMatching($query->status, 'REJECTED')}}>Rejected</option>
            </select>
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-md-10 col-md-offset-2">
                <button type="reset" class="btn btn-default" data-back>Back</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection
