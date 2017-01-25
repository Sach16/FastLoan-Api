@extends('admin.v1.layouts.master')

@section('main')
    <h1>Add new campaign</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.campaigns.store') }}" class="form-horizontal" method="post">
        {{ csrf_field() }}

        {{--Campaign Name--}}
        <div class="form-group col-md-9 {{ hasErrors('name', $errors) }}">
            <label class="control-label" for="inputName">Name</label>
            <input class="form-control" id="inputName" type="text"
                   name="name"
                   value="{{ old('name') }}">
        </div>

        {{--Campaign Organizer--}}
        <div class="form-group col-md-9 {{ hasErrors('organizer', $errors) }}">
            <label class="control-label" for="inputOrganizer">Organizer</label>
            <input class="form-control" id="inputOrganizer" type="text"
                   name="organizer"
                   value="{{ old('organizer') }}">
        </div>

        {{--Campaign Description--}}
        <div class="form-group col-md-9 {{ hasErrors('description', $errors) }}">
            <label class="control-label" for="inputDescription">Description</label>
            <textarea name="description" id="inputDescription" cols="30" rows="5"
                      class="form-control">{{ old('description') }}</textarea>
        </div>

        {{--Campaign Promotionals--}}
        <div class="form-group col-md-9 {{ hasErrors('promotionals', $errors) }}">
            <label class="control-label" for="inputPromotionals">Promotional Materials</label>
            <textarea name="promotionals" id="inputPromotionals" cols="30" rows="5"
                      class="form-control">{{ old('promotionals') }}</textarea>
        </div>

        {{--Campaign From--}}
        <div class="form-group col-md-9 {{ hasErrors('from', $errors) }}">
            <label class="control-label" for="inputFromDateTimeCreate">From Date</label>
            <input class="form-control" id="inputFromDateTimeCreate" type="text"
                   name="from"
                   value="{{ old('from') }}">
        </div>

        {{--Campaign From--}}
        <div class="form-group col-md-9 {{ hasErrors('to', $errors) }}">
            <label class="control-label" for="inputToDateTimeCreate">To Date</label>
            <input class="form-control" id="inputToDateTimeCreate" type="text"
                   name="to"
                   value="{{ old('to') }}">
        </div>

        {{--Campaign Type--}}
        <div class="form-group col-md-9 {{ hasErrors('type', $errors) }}">
            <label class="control-label" for="inputType">Type</label>
            <select name="type" id="inputType" class="form-control" data-chosen>
                <option value="GROUND_EVENT" {{ isMatching('GROUND_EVENT', old('type')) }}>Ground Event</option>
                <option value="SMS_CAMPAIGN" {{ isMatching('SMS_CAMPAIGN', old('type')) }}>SMS Campaign</option>
                <option value="EMAIL_CAMPAIGN" {{ isMatching('EMAIL_CAMPAIGN', old('type')) }}>Email Campaign</option>
            </select>
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

        {{--Team--}}
        <div class="form-group col-md-7 {{ hasErrors('team', $errors) }}">
            <label class="control-label" for="inputTeam">Team</label>
            <input type="hidden" value="{{ old('team') }}" id="selected-team">
            <select name="team" id="inputTeam" class="form-control"></select>
        </div>

        {{--Members--}}
        <div class="form-group col-md-6 {{ hasErrors('members', $errors) }}">
            <label class="control-label" for="inputMembers">Members</label>
            <input type="hidden" value="{{ old('members') ? implode(', ',old('members')):'' }}" id="selected-teamMembers">
            <select name="members[]" id="inputMembers" class="form-control" multiple data-chosen></select>
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                 <a href="{{ route('admin.v1.campaigns.index') }}" class="btn btn-default">Back</a>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection
