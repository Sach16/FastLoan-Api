@extends('admin.v1.layouts.master')

@section('main')
    <h1>Add new project</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.projects.store') }}" class="form-horizontal"
        enctype="multipart/form-data" method="post">
        {{ csrf_field() }}

        {{--Project picture--}}
        <div class="form-group col-md-8 {{ hasErrors('project_picture', $errors) }}">
            <label class="control-label" for="inputProjectPicture">Project picture</label>
            <input type="file" class="form-control" id="inputProjectPicture" name="project_picture">
                <span class="help-block">
                    <small>
                        <em>Only Image files with maximum 100 x 100 resolution </em>
                    </small>
                </span>
        </div>

        {{--Project Name--}}
        <div class="form-group col-md-8 {{ hasErrors('project', $errors) }}">
            <label class="control-label" for="inputProjectName">Project Name</label>
            <input type="text" class="form-control" id="inputProjectName" name="project" value="{{ old('project') }}">
        </div>

        {{--Builder Name--}}
        <div class="form-group col-md-8 {{ hasErrors('builder_id', $errors) }}">
            <label class="control-label" for="inputBuilderName">Builder Name</label>
            <input type="hidden" value="{{ old('builder_id') }}" id="selected-builder-name">
            <select name="builder_id" id="inputBuilderName" class="form-control" data-builders></select>
        </div>

        {{--Owner Name--}}
        <div class="form-group col-md-8 {{ hasErrors('owner_id', $errors) }}">
            <label class="control-label" for="inputOwnerName">Owner Name</label>
            <input type="hidden" value="{{ old('owner_id') }}" id="selected-owner-name">
            <select name="owner_id" id="inputOwnerName" class="form-control" data-dsa-owners></select>
        </div>

        {{--Units Available--}}
        <div class="form-group col-md-8 {{ hasErrors('units', $errors) }}">
            <label class="control-label" for="inputUnitsAvailable">Units available</label>
            <input type="text" class="form-control" id="inputUnitsAvailable"
                   name="units" value="{{ old('units') }}">
        </div>

        {{--Possession Date--}}
        <div class="form-group col-md-8 {{ hasErrors('possession', $errors) }}">
            <label class="control-label" for="inputPossessionDate">Possession Date</label>
            <input type="text" class="form-control" id="inputPossessionDate" data-date
                   name="possession" value="{{ old('possession') }}">
        </div>

        {{--Status--}}
        <div class="form-group col-md-8 {{ hasErrors('status_id', $errors) }}">
            <label class="control-label" for="inputStatus">Status</label>
            <input type="hidden" value="{{ old('status_id') }}" id="selected-status">
            <select name="status_id" id="inputStatus" class="form-control" data-chosen>
                <option selected="" disabled="">Select status</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->id }}" {{ isSelected($status->id,[old('status_id')])}}>{{ $status->label }}</option>
                @endforeach
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
        <div class="form-group col-md-8 {{ hasErrors('city', $errors) }}">
            <label class="control-label" for="inputCity">City</label>
            <input type="hidden" value="{{ old('city') }}" id="selected-city">
            <select name="city" id="inputCity" class="form-control" data-city></select>
        </div>

        {{--State--}}
        <div class="form-group col-md-8 {{ hasErrors('state', $errors) }}">
            <label class="control-label" for="inputState">State</label>
            <input type="hidden" value="{{ old('state') }}" id="selected-state">
            <input type="text" class="form-control" id="inputState" name="state" value="{{ old('state') }}">
        </div>

        {{--Country--}}
        <div class="form-group col-md-8 {{ hasErrors('country', $errors) }}">
            <label class="control-label" for="inputCountry">Country</label>
            <input type="text" class="form-control" id="inputCountry" name="country" value="{{ old('country') }}">
        </div>

        {{--Zipcode--}}
        <div class="form-group col-md-6 {{ hasErrors('zip', $errors) }}">
            <label class="control-label" for="inputZipCode">Pincode</label>
            <input type="text" class="form-control" id="inputZipCode" name="zip" value="{{ old('zip') }}">
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-md-10 col-md-offset-2">
                <button type="reset" class="btn btn-default">Reset</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection
