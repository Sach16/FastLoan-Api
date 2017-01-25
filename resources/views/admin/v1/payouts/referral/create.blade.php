@extends('admin.v1.layouts.master')
@section('main')
<h1>Add new referral payout</h1>
@include('admin.v1.layouts.partials._messages')
<hr>
<form action="{{ route('admin.v1.payouts.referral.store') }}" class="form-horizontal" method="post">
    {{ csrf_field() }}

    {{-- Referrals --}}
    <div class="form-group col-md-8 {{ hasErrors('user_id', $errors) }} ">
        <label class="control-label" for="inputUser">Referral Name</label>
        <input type="hidden" value="{{ old('user_id') }}" id="selected-referrals">
        <select name="user_id" id="inputUser" class="form-control" data-payout-referrals></select>
    </div>

    {{--Percentage--}}
    <div class="form-group col-md-8 {{ hasErrors('percentage', $errors) }}">
        <label class="control-label" for="inputPercentage">Percentage</label>
        <input type="text" class="form-control"
               id="inputPercentage" name="percentage"
               value="{{ old('percentage') }}">
    </div>

    {{--Submit--}}
    <div class="form-group">
        <div class="col-lg-10 col-lg-offset-2">
            <button type="reset" class="btn btn-default">Reset</button>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>

</form>
@endsection