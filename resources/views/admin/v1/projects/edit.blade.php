@extends('admin.v1.layouts.master')

@section('main')
    <h1>Edit project</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.projects.update', $project->id) }}" class="form-horizontal"
        enctype="multipart/form-data"  method="post">
        {{ csrf_field() }}
        {{ method_field('put') }}

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
            <input type="text" class="form-control" id="inputProjectName" name="project" value="{{ $project->name }}">
        </div>

        {{--Builder Name--}}
        <div class="form-group col-md-8 {{ hasErrors('builder', $errors) }}">
            <label class="control-label" for="inputBuilderName">Builder Name</label>
            <input type="hidden" name="builder_id" value="{{ $project->builder->id }}">
            <input type="text" class="form-control" id="inputBuilderName" readonly
                   name="builderName" value="{{ $project->builder->name }}">
        </div>

        {{--Owner Name--}}
        <div class="form-group col-md-8 {{ hasErrors('owner', $errors) }}">
            <label class="control-label" for="inputOwnerName">Owner Name</label>
            <input type="hidden" name="owner_id" value="{{ $project->owner->id }}">
            <input type="text" class="form-control" id="inputOwnerName"
                   readonly value="{{ $project->owner->present()->name }}">
        </div>

        {{--Units Available--}}
        <div class="form-group col-md-8 {{ hasErrors('units', $errors) }}">
            <label class="control-label" for="inputUnitsAvailable">Units available</label>
            <input type="text" class="form-control" id="inputUnitsAvailable"
                   name="units" value="{{ $project->unit_details }}">
        </div>

        {{--Possession Date--}}
        <div class="form-group col-md-8 {{ hasErrors('possession', $errors) }}">
            <label class="control-label" for="inputPossessionDate">Possession Date</label>
            <input type="text" class="form-control" id="inputPossessionDate" data-date
                   name="possession" value="{{ $project->present()->possessionInput }}">
        </div>

        {{--Status--}}
        <div class="form-group col-md-8 {{ hasErrors('status', $errors) }}">
            <label class="control-label" for="inputStatus">Status</label>
            <select name="status_id" id="inputStatus" class="form-control" data-chosen>
            <option>Select status</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->id }}" {{ isMatching($status->id, $project->status_id) }}>
                        {{ $status->label }}
                    </option>
                @endforeach
            </select>
        </div>

        {{--Aplha Street--}}
        <div class="form-group col-md-10 {{ hasErrors('alphaStreet', $errors) }}">
            <label class="control-label" for="inputAlphaStreet">Street #1</label>
            <input type="text" class="form-control" id="inputAlphaStreet" name="alphaStreet"
                   value="{{ $project->addresses()->first()->alpha_street }}">
        </div>

        {{--Beta Street--}}
        <div class="form-group col-md-10 {{ hasErrors('betaStreet', $errors) }}">
            <label class="control-label" for="inputBetaStreet">Street #2</label>
            <input type="text" class="form-control" id="inputBetaStreet" name="betaStreet"
                   value="{{ $project->addresses()->first()->beta_street }}">
        </div>

        {{--City--}}
        <div class="form-group col-md-8 {{ hasErrors('city', $errors) }}">
            <label class="control-label" for="inputCity">City</label>
            <input type="hidden" value="{{ ($project->addresses->count() > 0 ) ? $project->addresses->first()->city->id : "" }}" id="selected-city">
            <select name="city" id="inputCity" class="form-control" data-city>
            </select>
        </div>

        {{--State--}}
        <div class="form-group col-md-8 {{ hasErrors('state', $errors) }}">
            <label class="control-label" for="inputState">State</label>
            <input type="text" class="form-control" id="inputState" name="state"
                   value="{{ $project->addresses()->first()->state }}">
        </div>

        {{--Country--}}
        <div class="form-group col-md-8 {{ hasErrors('country', $errors) }}">
            <label class="control-label" for="inputCountry">Country</label>
            <input type="text" class="form-control" id="inputCountry" name="country"
                   value="{{ $project->addresses()->first()->country }}">
        </div>

        {{--Zipcode--}}
        <div class="form-group col-md-6 {{ hasErrors('zip', $errors) }}">
            <label class="control-label" for="inputZipCode">Pincode</label>
            <input type="text" class="form-control" id="inputZipCode" name="zip"
                   value="{{ $project->addresses()->first()->zip }}">
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-md-10 col-md-offset-2">
                <a href="{{ route('admin.v1.projects.show', $project->id) }}" class="btn btn-default" >Back</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection
