@extends('admin.v1.layouts.master')

@section('main')
    <h1>Add new bank</h1>
    @include('admin.v1.layouts.partials._messages')
    <form action="{{ route('admin.v1.banks.store') }}" class="form-horizontal"
        enctype="multipart/form-data" method="post">
        {{ csrf_field() }}

        {{--Bank picture--}}
        <div class="form-group col-md-8 {{ hasErrors('bank_picture', $errors) }}">
            <label class="control-label" for="inputBankPicture">Bank picture</label>
            <input type="file" class="form-control" id="inputBankPicture" name="bank_picture" >
                <span class="help-block">
                    <small>
                        <em>Only Image files with maximum 100 x 100 resolution </em>
                    </small>
                </span>
        </div>

        {{--Bank Name--}}
        <div class="form-group col-md-8 {{ hasErrors('bankName', $errors) }}">
            <label class="control-label" for="inputBankName">Bank Name</label>
            <input type="text" class="form-control" id="inputBankName" name="bankName" value="{{ old('bankName') }}">
        </div>

        {{--Branch--}}
        <div class="form-group col-md-8 {{ hasErrors('branch', $errors) }}">
            <label class="control-label" for="inputBranch">Branch</label>
            <input type="text" class="form-control" id="inputBranch" name="branch" value="{{ old('branch') }}">
        </div>

        {{--IFSC--}}
        <div class="form-group col-md-8 {{ hasErrors('ifsc', $errors) }}">
            <label class="control-label" for="inputIfsc">IFSC</label>
            <input type="text" class="form-control" id="inputIfsc" name="ifsc" value="{{ old('ifsc') }}">
        </div>

        {{--Aplha Street--}}
        <div class="form-group col-md-10 {{ hasErrors('alphaStreet', $errors) }}">
            <label class="control-label" for="inputAlphaStreet">Street #1</label>
            <input type="text" class="form-control" id="inputAlphaStreet" name="alphaStreet" value="{{ old('alphaStreet') }}">
        </div>

        {{--Beta Street--}}
        <div class="form-group col-md-10 {{ hasErrors('betaStreet', $errors) }}">
            <label class="control-label" for="inputBetaStreet">Street #2</label>
            <input type="text" class="form-control" id="inputBetaStreet" name="betaStreet" value="{{ old('betaStreet') }}">
        </div>

        {{--City--}}
        <div class="form-group col-md-8 {{ hasErrors('city_id', $errors) }}">
            <label class="control-label" for="inputCity">City</label>
            <input type="hidden" value="{{ old('city_id') }}" id="selected-city">
            <select name="city_id" id="inputCity" class="form-control" data-city></select>
        </div>

        {{--State--}}
        <div class="form-group col-md-8 {{ hasErrors('state', $errors) }}">
            <label class="control-label" for="inputState">State</label>
            <input type="text" class="form-control" id="inputState" name="state" value="{{ old('state') }}">
        </div>

        {{--Country--}}
        <div class="form-group col-md-8 {{ hasErrors('country', $errors) }}">
            <label class="control-label" for="inputCountry">Country</label>
            <input type="text" class="form-control" id="inputCountry" name="country" value="{{ old('country') }}">
        </div>

        {{--Zipcode--}}
        <div class="form-group col-md-6 {{ hasErrors('zipcode', $errors) }}">
            <label class="control-label" for="inputZipCode">Pincode</label>
            <input type="text" class="form-control" id="inputZipCode" name="zipcode" value="{{ old('zipcode') }}">
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
