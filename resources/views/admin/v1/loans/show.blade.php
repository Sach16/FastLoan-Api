@extends('admin.v1.layouts.master')

@section('main')


@include('admin.v1.layouts.partials._messages')

<h1>{{$loan->type->name}} Details</h1>

<hr>

<small><strong>DSA Name</strong>:
@if($loan->agent)
    {{$loan->agent->present()->name}}
@endif
</small>
<br>
<small><strong>Lead Name</strong>: {{$loan->userTrashed->present()->name}}</small>
<br>
<small><strong>Loan Type</strong>: {{$loan->typeTrashed->name}}</small>
<br>
<small><strong>Builder Name</strong>: {{@$loan->project->first()->builder->name}}</small>
<br>
<small><strong>Project Name</strong>: {{@$loan->project->first()->name}}</small>
<br>
<small><strong>Status</strong>: {{ $loan->statusTrashed->label }}</small>
<br>
<small><strong>Amount</strong>: {{ $loan->amount }}</small>
<br>
<small><strong>Eligible amount</strong>: {{ $loan->eligible_amount }}</small>
<br>
<small><strong>Approved amount</strong>: {{ $loan->approved_amount }}</small>
<br>
<small><strong>Interest rate</strong>: {{ $loan->interest_rate }}</small>
<br>
<small><strong>Applied on</strong>: {{ $loan->applied_on }}</small>
<br>
<small><strong>Approval date</strong>: {{ $loan->approval_date }}</small>
<br>
<small><strong>EMI start date</strong>: {{ $loan->emi_start_date }}</small>
<br>
<small><strong>EMI</strong>: {{ $loan->emi }}</small>
<br>
<small><strong>Appid</strong>: {{ $loan->appid }}</small>
<br>

<small><strong>Created date </strong>: {{ $loan->created_at }}</small>

<br><br>
    <a href="{{ route('admin.v1.loans.edit', $loan->id) }}" class="btn btn-xs btn-info">Edit</a>
    @if( !$loan->userTrashed->trashed() && !$loan->statusTrashed->trashed() && !$loan->typeTrashed->trashed() && $loan->tasks->count() <= 0)
        <form action="{{ route('admin.v1.loans.destroy', $loan->id) }}" class="form-inline inline-block" method="post">
            {{ csrf_field() }}
            {{ method_field('delete') }}
            <button class="btn btn-xs btn-{{$loan->trashed()? 'danger' : 'success'}}" data-status-confirm>{{$loan->trashed()? 'Enable Loan' : 'Disable Loan'}}</button>
        </form>
    @else
            <button type="button" class="btn btn-default btn-xs" data-container="body" data-toggle="popover" data-placement="bottom"  data-content="Cannot enable loan with out
            {{ $loan->userTrashed->trashed() ? '<li>active loan owner.</li>' : '' }}
            {{ $loan->statusTrashed->trashed() ? '<li>active loan status.</li>' : '' }}
            {{ $loan->typeTrashed->trashed() ? '<li>active product.</li>' : '' }}
            {{ $loan->tasks->count() > 0 ? '<li>active tasks.</li>' : '' }}
            " data-original-title="" title="">Enable Loan
        </button>
    @endif
<hr>
<h3>Loan Documents</h3>
<div class="row col-md-12">
    <a href="{{ route('admin.v1.loans.documents.create', $loan->id) }}" class="btn btn-xs btn-primary">Add new document</a>
</div>
<br>
<div class="row col-md-12">
    @foreach($loan->attachments as $attachment)
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
                <a href="{{ route('admin.v1.loans.documents.edit', [
                            'loans' => $loan->id, 'documents' => $attachment->id
                        ]) }}"
                   class="btn btn-xs btn-warning">Edit</a>

                <form
                        action="{{ route('admin.v1.loans.documents.destroy',
                            ['loans' => $loan->id, 'documents' => $attachment->id]) }}"
                        class="form-horizontal inline-block" method="post"
                >
                    {{ csrf_field() }}
                    {{ method_field('delete') }}
                    <button type="submit" class="btn btn-xs btn-danger" data-confirm>Delete</button>
                </form>
            </div>
        </div>
    @endforeach
</div>
@endsection
