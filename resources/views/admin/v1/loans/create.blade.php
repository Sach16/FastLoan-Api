@extends('admin.v1.layouts.master')

@section('main')


@include('admin.v1.layouts.partials._messages')

<h1>Add new Loan</h1>

<hr/>
<form action="{{ route('admin.v1.loans.store') }}" class="form-horizontal" method="post">

    {{ csrf_field() }}

    <input type="hidden" name="user_id" value="{{ $lead->user->id}}" >
    <input type="hidden" name="lead_id" value="{{ $lead->id}}" >

    {{--Owner --}}
    <div class="form-group col-md-8 {{ hasErrors('first_name', $errors) }}">
        <label class="control-label" for="inputFirstName">Owner</label>
        <input type="text" class="form-control" id="inputFirstName" name="first_name" value="{{ $lead->user->present()->name }}" disabled>
    </div>

    {{--Agent --}}
    <div class="form-group col-md-8 {{ hasErrors('agent_id', $errors) }}">
        <label class="control-label" for="inputAgentId">DSA</label>
        <input type="hidden" value="{{ old('agent_id') }}" id="selected-member">
        <select name="agent_id" id="inputAgentId" class="form-control" data-members></select>
    </div>


    {{--Status --}}
    <div class="form-group col-md-8 {{ hasErrors('status', $errors) }}">
        <label class="control-label" for="inputLoanStatusId">Status</label>
        <input type="hidden" value="{{ old('agent_id') }}" id="selected-loan-status">
        <select name="loan_status_id" id="inputLoanStatusId" class="form-control" data-loan-statuses></select>
    </div>

    {{--Appid --}}
    <div class="form-group col-md-8 {{ hasErrors('appid', $errors) }}">
        <label class="control-label" for="inputAppid">Appid</label>
        <input type="text" class="form-control" id="inputAppid" name="appid" value="{{ old('agent_id') }}" >
    </div>


    {{--Type --}}
    <div class="form-group col-md-8 {{ hasErrors('type_id', $errors) }}">
        <label class="control-label" for="inputLoanType">Loan Type</label>
        <input type="hidden" value="{{ old('type_id') }}" id="selected-loan-types">
        <select name="type_id" id="inputLoanType" class="form-control" data-loan-types></select>
    </div>

    {{--Property Verified--}}
    <div class="form-group col-md-8 {{ hasErrors('property_verified', $errors) }}" id="divPropertyVerified" {{ old('type_id') !='1'?'hidden':'' }}>
        <label class="control-label" for="inputPropertyVerified">Property Verified</label>
        <select name="property_verified" id="inputPropertyVerified" class="form-control" data-chosen disabled>
            <option value="-1" disabled selected>Select property verified</option>
            <option value="1" {{ isMatching('1', old('property_verified')) }}>
                Yes
            </option>
            <option value="0" {{ isMatching('0', old('property_verified')) }}>
                No
            </option>
        </select>
    </div>
    
    {{-- Builders --}}
    <div class="form-group col-md-8 {{ hasErrors('builder_id', $errors) }}" id="divBuilder" {{ old('type_id') !='1'?'hidden':'' }}>
        <label class="control-label" for="inputBuilder">Builders</label>
        <input type="hidden" value="{{old('builder_id') }}" id="selected-builders">
        <select name="builder_id" id="inputBuilder" class="form-control" data-builders >
        </select>
    </div>


    {{-- Projects --}}
    <div class="form-group col-md-8 {{ hasErrors('project_id', $errors) }}" id="divProjects" {{ old('type_id') !='1'?'hidden':'' }}>
        <label class="control-label" for="inputProject">Projects</label>
        <input type="hidden" value="{{ old('project_id') }}" id="selected-projects">
        <select name="project_id" id="inputProject" class="form-control" data-projects >
        </select>
    </div>
    
    {{--Source--}}
    <div class="form-group col-md-8 {{ hasErrors('source_id', $errors) }}">
        <label class="control-label" for="inputSourceId">Source</label>
            <input type="hidden" value="{{ old('source_id') }}" id="selected-source">
        <select name="source_id" id="inputSourceId" class="form-control" data-sources></select>
    </div>


    {{-- Referrals --}}
    <div class="form-group col-md-8 {{ hasErrors('referral_id', $errors) }} ">
        <label class="control-label" for="inputReferral">Referral</label>
        <input type="hidden" value="{{ old('referral_id') }}" id="selected-referrals">
        <select name="referral_id" id="inputReferral" class="form-control" data-referrals disabled></select>
    </div>

    {{--Amount --}}
    <div class="form-group col-md-8 {{ hasErrors('amount', $errors) }}">
        <label class="control-label" for="inputAmount">Amount</label>
        <input type="text" class="form-control" id="inputAmount" name="amount" value="{{ old('amount') }}">
    </div>


    {{--Eligible amount --}}
    <div class="form-group col-md-8 {{ hasErrors('eligible_amount', $errors) }}">
        <label class="control-label" for="inputEligibleAmount">Eligible amount</label>
        <input type="text" class="form-control" id="inputEligibleAmount" name="eligible_amount" value="{{ old('eligible_amount') }}">
    </div>


    {{--Approved amount --}}
    <div class="form-group col-md-8 {{ hasErrors('approved_amount', $errors) }}">
        <label class="control-label" for="inputApprovedAmount">Approved amount</label>
        <input type="text" class="form-control" id="inputApprovedAmount" name="approved_amount" value="{{ old('approved_amount') }}">
    </div>

    {{--Interest Rate --}}
    <div class="form-group col-md-8 {{ hasErrors('interest_rate', $errors) }}">
        <label class="control-label" for="inputInterestRate">Interest rate</label>
        <input type="text" class="form-control" id="inputInterestRate" name="interest_rate" value="{{ old('interest_rate') }}">
    </div>


    {{--EMI --}}
    <div class="form-group col-md-8 {{ hasErrors('emi', $errors) }}">
        <label class="control-label" for="inputEmi">Emi</label>
        <input type="text" class="form-control" id="inputEmi" name="emi" value="{{ old('emi') }}">
    </div>

    {{--EMI Start date--}}
    <div class="form-group col-md-8 {{ hasErrors('emi_start_date', $errors) }}">
        <label class="control-label" for="inputEmiStartDate">Emi start date</label>
        <input type="text" class="form-control" id="inputEmiStartDate" name="emi_start_date" value="{{ old('emi_start_date') }}" data-date>
    </div>

    {{--Submit--}}
    <div class="form-group">
        <div class="col-lg-10 col-lg-offset-2">
            <button type="reset" class="btn btn-default" data-back>Back</button>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>

</form>

@endsection
