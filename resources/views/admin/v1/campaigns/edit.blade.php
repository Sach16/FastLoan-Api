@extends('admin.v1.layouts.master')

@section('main')
    <h1>Edit campaign</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.campaigns.update', $campaign->id) }}" class="form-horizontal" method="post">
        {{ csrf_field() }}
        {{ method_field('put') }}

        {{--Campaign Name--}}
        <div class="form-group col-md-9 {{ hasErrors('name', $errors) }}">
            <label class="control-label" for="inputName">Name</label>
            <input class="form-control" id="inputName" type="text"
                   name="name"
                   value="{{ $campaign->name }}">
        </div>

        {{--Campaign Organizer--}}
        <div class="form-group col-md-9 {{ hasErrors('organizer', $errors) }}">
            <label class="control-label" for="inputOrganizer">Organizer</label>
            <input class="form-control" id="inputOrganizer" type="text"
                   name="organizer"
                   value="{{ $campaign->organizer }}">
        </div>

        {{--Campaign Description--}}
        <div class="form-group col-md-9 {{ hasErrors('description', $errors) }}">
            <label class="control-label" for="inputDescription">Description</label>
            <textarea name="description" id="inputDescription" cols="30" rows="5"
                      class="form-control">{{ $campaign->description }}</textarea>
        </div>

        {{--Campaign Promotionals--}}
        <div class="form-group col-md-9 {{ hasErrors('promotionals', $errors) }}">
            <label class="control-label" for="inputPromotionals">Promotional Materials</label>
            <textarea name="promotionals" id="inputPromotionals" cols="30" rows="5"
                      class="form-control">{{ $campaign->promotionals }}</textarea>
        </div>

        {{--Campaign From--}}
        <div class="form-group col-md-9 {{ hasErrors('from', $errors) }}">
            <label class="control-label" for="CampaignEditInputFrom">From Date</label>
            <input type="hidden" id="selected-CampaignEditInputFrom" value="{{ date('Y-m-d H:i:s',strtotime($campaign->present()->fromInput)) }}">
            <input class="form-control" id="CampaignEditInputFrom" type="text"
                   name="from"
                   value="{{ $campaign->present()->fromInput }}">
        </div>

        {{--Campaign To--}}
        <div class="form-group col-md-9 {{ hasErrors('to', $errors) }}">
            <label class="control-label" for="CampaignEditInputTo">To Date</label>
            <input type="hidden" value="{{ date('Y-m-d H:i:s',strtotime($campaign->from)) }}" id="selected-CampaignEditInputTo">
            <input class="form-control" id="CampaignEditInputTo" type="text"
                   name="to"
                   value="{{ $campaign->present()->toInput }}">
        </div>

        {{--Campaign Type--}}
        <div class="form-group col-md-9 {{ hasErrors('type', $errors) }}">
            <label class="control-label" for="inputType">Type</label>
            <select name="type" id="inputType" class="form-control" data-chosen>
                <option value="GROUND_EVENT" {{ isSelected('GROUND_EVENT', [$campaign->type]) }}>Ground Event</option>
                <option value="SMS_CAMPAIGN" {{ isSelected('SMS_CAMPAIGN', [$campaign->type]) }}>SMS Campaign</option>
                <option value="EMAIL_CAMPAIGN" {{ isSelected('EMAIL_CAMPAIGN', [$campaign->type]) }}>Email Campaign</option>
            </select>
        </div>

        {{--Aplha Street--}}
        <div class="form-group col-md-10 {{ hasErrors('alphaStreet', $errors) }}">
            <label class="control-label" for="inputAlphaStreet">Street #1</label>
            <input type="text" class="form-control" id="inputAlphaStreet" name="alphaStreet"
                   value="{{ $campaign->addresses->first()->alpha_street }}">
        </div>

        {{--Beta Street--}}
        <div class="form-group col-md-10 {{ hasErrors('betaStreet', $errors) }}">
            <label class="control-label" for="inputBetaStreet">Street #2</label>
            <input type="text" class="form-control" id="inputBetaStreet" name="betaStreet"
                   value="{{ $campaign->addresses->first()->beta_street }}">
        </div>

        {{--City--}}
        <div class="form-group col-md-8 {{ hasErrors('city', $errors) }}">
            <label class="control-label" for="inputCity">City</label>
            <input type="hidden" value="{{ ($campaign->addresses->count() > 0 ) ? $campaign->addresses->first()->city->id : "" }}" id="selected-city">
            <select name="city" id="inputCity" class="form-control" data-city>
            </select>
        </div>

        {{--State--}}
        <div class="form-group col-md-8 {{ hasErrors('state', $errors) }}">
            <label class="control-label" for="inputState">State</label>
            <input type="text" class="form-control" id="inputState" name="state"
                   value="{{ $campaign->addresses->first()->state }}">
        </div>

        {{--Country--}}
        <div class="form-group col-md-8 {{ hasErrors('country', $errors) }}">
            <label class="control-label" for="inputCountry">Country</label>
            <input type="text" class="form-control" id="inputCountry" name="country"
                   value="{{ $campaign->addresses->first()->country }}">
        </div>

        {{--Zipcode--}}
        <div class="form-group col-md-6 {{ hasErrors('zipcode', $errors) }}">
            <label class="control-label" for="inputZipCode">Pincode</label>
            <input type="text" class="form-control" id="inputZipCode" name="zip"
                   value="{{ $campaign->addresses->first()->zip }}">
        </div>

        {{--Team--}}
        <div class="form-group col-md-7 {{ hasErrors('team', $errors) }}">
            <label class="control-label" for="inputTeam">Team</label>
            <input type="hidden" name="team" value="{{ $campaign->team->id }}">
            <input type="text" readonly class="form-control"
                   value="{{ $campaign->team->name }}">
        </div>

        {{--Members--}}
        <div class="form-group col-md-6 {{ hasErrors('members', $errors) }}">
            <label class="control-label" for="inputMembers">Members</label>
            <select name="members[]" id="inputMembers" class="form-control" multiple data-chosen>
                @foreach($campaign->team->members as $member)
                    <option value="{{ $member->id }}"
                            {{ isSelected($member->id, $campaign->members->lists('id')->toArray()) }}>
                        {{ $member->present()->name}}
                    </option>
                @endforeach
            </select>
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <a href="{{ route('admin.v1.campaigns.show', $campaign->id) }}" class="btn btn-default" >Back</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection
