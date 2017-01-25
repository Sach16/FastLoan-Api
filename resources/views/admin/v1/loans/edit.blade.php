@extends('admin.v1.layouts.master')

@section('main')


@include('admin.v1.layouts.partials._messages')

<h1>Edit {{$loan->type->name}} Details</h1>

<hr/>
<form action="{{ route('admin.v1.loans.update', $loan->id) }}" class="form-horizontal" method="post">

    {{ csrf_field() }}
    {{ method_field('put') }}

    {{--Owner --}}
    <div class="form-group col-md-8 {{ hasErrors('first_name', $errors) }}">
        <label class="control-label" for="inputFirstName">Owner</label>
        <input type="text" class="form-control" id="inputFirstName" name="first_name" value="{{ $loan->user->present()->name }}" disabled>
    </div>

    {{--Agent --}}
    <div class="form-group col-md-8 {{ hasErrors('agent_id', $errors) }}">
        <label class="control-label" for="inputAgentId">DSA</label>
        <input type="hidden" value="{{ $loan->agent ? $loan->agent->id : '' }}" id="selected-member">
        <select name="agent_id" id="inputAgentId" class="form-control" data-members></select>
    </div>

    {{--Status --}}
    <div class="form-group col-md-8 {{ hasErrors('status', $errors) }}">
        <label class="control-label" for="inputLoanStatusId">Status</label>
        <input type="hidden" value="{{ $loan->loan_status_id }}" id="selected-loan-status">
        <select name="loan_status_id" id="inputLoanStatusId" class="form-control" data-loan-statuses ></select>
    </div>

    {{--Disbursement amount --}}
    <div class="form-group col-md-8 {{ hasErrors('disbursement_amount', $errors) }}" id="Divdisbursementamount" <?php if ($loan->loan_status_id == 7) {
    echo "";
} else {
    echo "hidden";
}
?> >
        <label class="control-label" for="Inputdisbursementamount">Disbursement Amount</label>
        <input type="text" class="form-control" id="Inputdisbursementamount" name="disbursement_amount" value="{{ old('disbursement_amount') }}" id="disbursement_amount">
    </div>

    {{--Appid --}}
    <div class="form-group col-md-8 {{ hasErrors('appid', $errors) }}">
        <label class="control-label" for="inputAppid">App ID</label>
        <input type="text" class="form-control" id="inputAppid" name="appid" value="{{ $loan->appid }}" >
    </div>

    {{--Type --}}
    <div class="form-group col-md-8 {{ hasErrors('type_id', $errors) }}">
        <label class="control-label" for="inputLoanType">Loan Type</label>
        <input type="hidden" value="{{ $loan->type_id }}" name="type_id" id="selected-loan-types">
        <select name="type_id" id="inputLoanType" class="form-control" data-loan-types disabled=""></select>
    </div>

    {{--Property Verified--}}
    <div class="form-group col-md-8 {{ hasErrors('property_verified', $errors) }}" id="divPropertyVerified" {{ $loan->type_id !='1'?'hidden':'' }}>
        <label class="control-label" for="inputPropertyVerified">Property Verified</label>
        <select name="property_verified" id="inputPropertyVerified" class="form-control" data-chosen disabled>
            <option value="-1" disabled selected>Select property verified</option>
            <option value="1" {{ (($loan->project->count() != 0) ? 'selected' : "") }}>
                Yes
            </option>
            <option value="0" {{ (($loan->project->count() == 0) ? "selected" : "") }}>
                No
            </option>
        </select>
    </div>

    {{-- Builders --}}
    <div class="form-group col-md-8 {{ hasErrors('builder_id', $errors) }}">
        <label class="control-label" for="inputBuilder">Builders</label>
        <input type="hidden" value="{{ (($loan->project->count() != 0) ? $loan->project->first()->builder->id : "") }}" id="selected-builders">
        <select name="builder_id" id="inputBuilder" class="form-control" data-builders {{ (($loan->project->count() == 0)) ? "disabled" : "" }}></select>
    </div>


    {{-- Projects --}}
    <div class="form-group col-md-8 {{ hasErrors('project_id', $errors) }}">
        <label class="control-label" for="inputProject">Projects</label>
        <input type="hidden" value="{{ (($loan->project->count() != 0) ? $loan->project->first()->id : "") }}" id="selected-projects">
        <select name="project_id" id="inputProject" class="form-control" data-projects {{ (($loan->project->count() == 0)) ? "disabled" : "" }}>
        </select>
    </div>

    {{--Amount --}}
    <div class="form-group col-md-8 {{ hasErrors('amount', $errors) }}">
        <label class="control-label" for="inputAmount">Amount</label>
        <input type="text" class="form-control" id="inputAmount" name="amount" value="{{ $loan->amount }}">
    </div>

    {{--Eligible amount --}}
    <div class="form-group col-md-8 {{ hasErrors('eligible_amount', $errors) }}">
        <label class="control-label" for="inputEligibleAmount">Eligible amount</label>
        <input type="text" class="form-control" id="inputEligibleAmount" name="eligible_amount" value="{{ $loan->eligible_amount }}">
    </div>

    {{--Approved amount --}}
    <div class="form-group col-md-8 {{ hasErrors('approved_amount', $errors) }}" id="DivApprovedAmount">
        <label class="control-label" for="inputApprovedAmount">Approved amount</label>
        <input type="text" class="form-control" id="inputApprovedAmount" name="approved_amount" value="{{ $loan->approved_amount }}" @if($loan->loan_status_id == 5)'' @endif 'disabled'>
    </div>

    {{--Interest Rate --}}
    <div class="form-group col-md-8 {{ hasErrors('interest_rate', $errors) }}">
        <label class="control-label" for="inputInterestRate">Interest rate</label>
        <input type="text" class="form-control" id="inputInterestRate" name="interest_rate" value="{{ $loan->interest_rate }}">
    </div>

    {{--EMI --}}
    <div class="form-group col-md-8 {{ hasErrors('emi', $errors) }}">
        <label class="control-label" for="inputEmi">EMI Amount</label>
        <input type="text" class="form-control" id="inputEmi" name="emi" value="{{ $loan->emi }}" @if($loan->loan_status_id == 5)'' @endif 'disabled'>
    </div>

    {{--EMI Start date--}}
    <div class="form-group col-md-8 {{ hasErrors('emi_start_date', $errors) }}">
        <label class="control-label" for="inputEmiStartDate">EMI start date</label>
        <input type="text" class="form-control" id="inputEmiStartDate" name="emi_start_date" value="{{ date('d-m-Y',strtotime($loan->emi_start_date)) }}" data-date @if($loan->loan_status_id == 5)'' @endif 'disabled'>
    </div>

    {{--Submit--}}
    <div class="form-group">
        <div class="col-lg-10 col-lg-offset-2">
            <button type="reset" class="btn btn-default" data-back>Back</button>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </div>

</form>

@endsection
