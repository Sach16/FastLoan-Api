@extends('admin.v1.layouts.master')

@section('main')
    <h1>Assign Team</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.referrals.team.update', $referral->id) }}" class="form-horizontal" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="referral_id" value="{{ $referral->id }}">

        {{--Referral--}}
        <div class="form-group col-md-8">
            <label class="control-label" for="inputCountry">Referral</label>
            <input type="text" readonly class="form-control" value="{{ $referral->present()->name }}">
        </div>

        {{--Team--}}
        <div class="form-group col-md-8 {{ hasErrors('team', $errors) }}">
            <label class="control-label" for="inputTeams">Team</label>
            <select name="team" id="inputTeams" class="form-control" data-chosen>
                <option disabled selected>Select Team</option>
                @foreach($teams as $team)
                    <option value="{{ $team->members->first()->id }}">{{ $team->members->first()->present()->name }} - {{ $team->name }}</option>
                @endforeach
            </select>
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <button type="reset" class="btn btn-default" data-back>Back</button>
                <button type="submit" class="btn btn-primary">Assign</button>
            </div>
        </div>
    </form>
@endsection