@extends('admin.v1.layouts.master')

@section('main')


@include('admin.v1.layouts.partials._messages')
    <div class="media">
        <div class="media-left">
            @if( ! $lead->userTrashed->attachments->isEmpty())
            <div class="media-left">
                <a href="{{ uploaded($lead->userTrashed->attachments->first()->uri) }}" target="_blank">
                    <img class="media-object img-rounded"
                         style="width: 230px ;"
                         src="{{ uploaded($lead->userTrashed->attachments->first()->uri) }}"
                         alt="{{ $lead->userTrashed->present()->name }}">
                </a>
            </div>
            @endif
        </div>
        <div class="media-body">
            <h1>Lead details</h1>
            <strong>Name</strong>: {{ $lead->userTrashed->first_name}} {{ $lead->userTrashed->last_name }}
            <br>
            <strong>Email ID</strong>: {{ $lead->userTrashed->email }}
            <br>
            <strong>Phone Number</strong>: {{ $lead->userTrashed->phone }}
            <br>
            <strong>Assigned to</strong>: {{ $assigned_user ? $assigned_user->present()->name :'-' }}
        </div>
    </div>
<br>
@if(!is_null($lead->userTrashed->settings))
    @foreach($lead->userTrashed->settings as $name => $value)
        <strong>{{ s2l($name) }}</strong>:
        <small><em>{{ s2l($value) }}</em></small>
    @endforeach
@endif

<br>
<strong>Existing Loan EMI Amount</strong>: {{ $lead->existing_loan_emi }}
<hr>

@if (\Auth::user()->role === 'SUPER_ADMIN' || \Auth::user()->role === 'DSA_OWNER')
<a href="{{ route('admin.v1.leads.edit', $lead->id) }}" class="btn btn-xs btn-info">Edit</a>
@if($lead->userTrashed->trashed())
    <form action="{{ route('admin.v1.leads.destroy', $lead->id) }}" class="form-inline inline-block" method="post">
        {{ csrf_field() }}
        {{ method_field('delete') }}
        <button class="btn btn-xs btn-{{$lead->userTrashed->trashed()? 'danger' : 'success'}}" data-status-confirm>{{$lead->userTrashed->trashed()? 'Enable lead' : 'Disable lead'}}</button>
    </form>
@elseif( $lead->user->loans->count() <=0  )
    <form action="{{ route('admin.v1.leads.destroy', $lead->id) }}" class="form-inline inline-block" method="post">
        {{ csrf_field() }}
        {{ method_field('delete') }}
        <button class="btn btn-xs btn-{{$lead->user->trashed()? 'danger' : 'success'}}" data-status-confirm>{{$lead->user->trashed()? 'Enable lead' : 'Disable lead'}}</button>
    </form>
@else
    <button type="button" class="btn btn-default btn-xs" data-container="body" data-toggle="popover" data-placement="bottom" data-content="{{ 'Cannot delete lead with active loan(s).' }}
    " data-original-title="" title="">Disable Lead
    </button>
@endif
<a href="{{ route('admin.v1.loans.create', ['id' => $lead->id]) }}" class="btn btn-xs btn-primary">Add new loan</a>
@endif

<h3>Loans</h3>
<table class="table table-condensed table-striped">
    <thead>
        <tr>
            <th>Type</th>
            <th>Agent</th>
            <th>Bank</th>
            <th>Amount</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
      @foreach($lead->userTrashed->loansTrashed as $loan)
        <tr>
            <td>{{ $loan->type->name }}</td>
            @if($loan->agent)
                <td>{{ $loan->agent->present()->name }}</td>
                @if(!$loan->agent->banks->isEmpty())
                    <td>{{ $loan->agent->banks->first()->name }}</td>
                @else
                    <td></td>
                @endif
            @else
                <td></td>
                <td></td>
            @endif
            <td>Rs. {{ $loan->present()->amountAsCurrency }}</td>
            <td>{{ indexStatus($loan) }}</td>
            <td class="pull-right">
            <a href="{{ route('admin.v1.loans.show', $loan->id) }}" class="btn btn-xs btn-info">
                Details
            </a>
        </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
