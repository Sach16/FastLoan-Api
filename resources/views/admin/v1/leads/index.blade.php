<?php $request = request()->all();?>

@extends('admin.v1.layouts.master')

@section('main')
<h1>View all leads</h1>
@include('admin.v1.layouts.partials._messages')
<hr>

<a href="{{ route('admin.v1.leads.bulk.export') }}?paginate=all&{{http_build_query(request()->all())}}" class="btn btn-xs btn-info sumbit">
    Export as excel
</a>

<form class="form-inline pull-right">
    <div class="form-group" action="">
        <div class="input-group">
            <select class="form-control col-md-2 input-sm" name="filter">
                <?php $filter = (isset($request['filter'])) ? $request['filter'] : ""?>
                <option value="" {{ isMatching("without-tasks", $filter) }}>All</option>
                <option value="without-tasks" {{ isMatching("without-tasks", $filter) }}>Leads without tasks</option>
            </select>
        </div>
    </div>
    {{ csrf_field() }}
    <button type="submit" class="btn btn-sm btn-primary">Go</button>
</form>

<table class="table table-condensed table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Phone Number</th>
            <th>Email ID</th>
            <th>Type</th>
            <th>Added by</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>
    <thead>
        <tr>
    <form class="form-inline" action="" id="table-search-form" data-col-search>
        <th>

        <div class="form-group {{ hasErrors('name-filter', $errors) }}">
            <input type="text" class="form-control input-sm" name="name-filter" value="{{ old('name-filter') }}" data-col-search>
        </div>
        </th>
        <th>
        <div class="form-group {{ hasErrors('phone-filter', $errors) }}">
            <input type="text" class="form-control input-sm" name="phone-filter" value="{{ old('phone-filter') }}" data-col-search>
        </div>
        </th>
        <th>
        <div class="form-group {{ hasErrors('email-filter', $errors) }}">
            <input type="text" class="form-control input-sm" name="email-filter" value="{{ old('email-filter') }}" data-col-search>
        </div>
        </th>
        <th>
        <div class="form-group {{ hasErrors('status-filter', $errors) }}">
            <input type="text" class="form-control input-sm" name="status-filter" value="{{ old('status-filter') }}" data-col-search>
        </div>
        </th>
        <th></th>
        <th class="pull-right">
            <a href="{{ route('admin.v1.leads.index') }}" class="btn btn-xs btn-info">
                Reset Search
            </a>
        </th>
    </form>
</tr>

</thead>
<tbody>
    @forelse($leads as $lead)
        <tr>
            <td>{{ $lead->userTrashed->present()->name }}</td>
            <td>{{ $lead->userTrashed->phone }}</td>
            <td>{{ $lead->userTrashed->email }}</td>
            <td>{{ $lead->loanTrashed->status->label }} </td>
            <td>{{  empty($lead->createdBy)  ? '-' : $lead->createdBy->present()->name }} </td>
            <td>{{ indexStatus($lead->userTrashed) }} </td>
            <td class="pull-right">
                <a href="{{ route('admin.v1.leads.show', $lead->id) }}" class="btn btn-xs btn-info">
                    Details
                </a>
            </td>
        </tr>
        @empty
            <tr class="text-info">
                <td colspan="6">No leads</td>
            </tr>
        @endforelse
</tbody>
</table>

{{ $leads->appends(request()->all())->render() }}


@endsection