@extends('admin.v1.layouts.master')

@section('main')
    <h1>Edit referral payout</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.payouts.referral.update', $referral->id) }}" class="form-horizontal" method="post">
        {{ csrf_field() }}
        {{ method_field('put') }}


         {{-- Referrals --}}
        <div class="form-group col-md-8 {{ hasErrors('user_id', $errors) }} ">
            <label class="control-label" for="inputUser">Referral Name</label>
            <input type="hidden" value="{{ $referral->id }}" id="selected-referrals">
            <input type="hidden" value="{{ $referral->id }}" name="user_id">
            <select name="user_id" id="inputUser" class="form-control" data-referrals disabled></select>
        </div>

         {{--Percentage--}}
        <div class="form-group col-md-8 {{ hasErrors('percentage', $errors) }}">
            <label class="control-label" for="inputPercentage">Percentage</label>
            <input type="text" class="form-control" id="inputPercentage" name="percentage" value="{{ $referral->payouts->first()->percentage }}">
        </div>

        {{--Total Paid Amount--}}
        <div class="form-group col-md-8 {{ hasErrors('total_paid_amount', $errors) }}">
            <label class="control-label" for="inputTotalPaidAmount">Total Paid Amount</label>
            <input type="text" class="form-control" id="inputTotalPaidAmount" name="total_paid_amount" value="{{ $referral->payouts->first()->total_paid_amount }}">
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <a href="{{ route('admin.v1.payouts.index') }}" class="btn btn-default" >Back</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection