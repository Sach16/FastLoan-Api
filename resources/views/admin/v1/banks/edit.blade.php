@extends('admin.v1.layouts.master')

@section('main')
    <h1>Edit bank</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.banks.update', $bank->id) }}" class="form-horizontal"
          enctype="multipart/form-data" method="post">
        {{ csrf_field() }}
        {{ method_field('put') }}

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
        <div class="form-group col-md-8 {{ hasErrors('name', $errors) }}">
            <label class="control-label" for="inputBankName">Bank Name</label>
            <input type="text" class="form-control" id="inputBankName" name="name" value="{{ $bank->name }}">
        </div>

        {{--Branch--}}
        <div class="form-group col-md-8 {{ hasErrors('branch', $errors) }}">
            <label class="control-label" for="inputBranch">Branch</label>
            <input type="text" class="form-control" id="inputBranch" name="branch" value="{{ $bank->branch }}">
        </div>

        {{--IFSC--}}
        <div class="form-group col-md-8 {{ hasErrors('ifsc_code', $errors) }}">
            <label class="control-label" for="inputIfsc">IFSC</label>
            <input type="text" class="form-control" id="inputIfsc" name="ifsc_code" value="{{ $bank->ifsc_code }}">
        </div>

        {{--Aplha Street--}}
        <div class="form-group col-md-10 {{ hasErrors('alphaStreet', $errors) }}">
            <label class="control-label" for="inputAlphaStreet">Street #1</label>
            <input type="text" class="form-control" id="inputAlphaStreet" name="alphaStreet"
                   value="{{ ($bank->addresses->count() > 0 ) ? $bank->addresses->first()->alpha_street : "" }}">
        </div>

        {{--Beta Street--}}
        <div class="form-group col-md-10 {{ hasErrors('betaStreet', $errors) }}">
            <label class="control-label" for="inputBetaStreet">Street #2</label>
            <input type="text" class="form-control" id="inputBetaStreet" name="betaStreet"
                   value="{{ ($bank->addresses->count() > 0 ) ? $bank->addresses->first()->beta_street : "" }}">
        </div>

        {{--City--}}
        <div class="form-group col-md-8 {{ hasErrors('city', $errors) }}">
            <label class="control-label" for="inputCity">City</label>
            <input type="hidden" value="{{ ($bank->addresses->count() > 0 ) ? $bank->addresses->first()->city->id : "" }}" id="selected-city">
            <select name="city" id="inputCity" class="form-control" data-city>
            </select>
        </div>

        {{--State--}}
        <div class="form-group col-md-8 {{ hasErrors('state', $errors) }}">
            <label class="control-label" for="inputState">State</label>
            <input type="text" class="form-control" id="inputState" name="state"
                   value="{{ ($bank->addresses->count() > 0 ) ? $bank->addresses->first()->state : "" }}">
        </div>

        {{--Country--}}
        <div class="form-group col-md-8 {{ hasErrors('country', $errors) }}">
            <label class="control-label" for="inputCountry">Country</label>
            <input type="text" class="form-control" id="inputCountry" name="country"
                   value="{{ ($bank->addresses->count() > 0 ) ? $bank->addresses->first()->country : "" }}">
        </div>

        {{--Zipcode--}}
        <div class="form-group col-md-6 {{ hasErrors('zip', $errors) }}">
            <label class="control-label" for="inputZipCode">Pincode</label>
            <input type="text" class="form-control" id="inputZipCode" name="zip"
                   value="{{ ($bank->addresses->count() > 0 ) ? $bank->addresses->first()->zip : "" }}">
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <a href="{{ route('admin.v1.banks.show', $bank->id)}}" class="btn btn-default">Back</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection
