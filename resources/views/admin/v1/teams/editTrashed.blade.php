@extends('admin.v1.layouts.master')

@section('main')
    <h1>Edit team</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.teams.update', $team->id) }}" class="form-horizontal" method="post">
        {{ csrf_field() }}
        {{ method_field('put') }}

        {{--Name--}}
        <div class="form-group col-md-8 {{ hasErrors('name', $errors) }}">
            <label class="control-label" for="inputTeamName">Name</label>
            <input type="text" class="form-control" id="inputTeamName" name="name" value="{{ $team->name }}">
        </div>

        {{--Description--}}
        <div class="form-group col-md-8 {{ hasErrors('description', $errors) }}">
            <label class="control-label" for="inputDescription">Description</label>
            <input type="text" class="form-control" id="inputDescription" name="description" value="{{ $team->description }}">
        </div>

        {{--Bank Code--}}
        <div class="form-group col-md-8 {{ hasErrors('bank_code', $errors) }}">
            <label class="control-label" for="inputBankCode">Bank Code</label>
            <input type="text" class="form-control" id="inputBankCode" name="bank_code" value="{{ $team->bank_code }}">
        </div>

        {{--Owner--}}
        <div class="form-group col-md-8 {{ hasErrors('owner', $errors) }}">
            <label class="control-label" for="inputOwner">Owner</label>
            <input type="hidden" id="selected-owner_id" value="{{ old('owner_id') }}">
            <select name="owner_id[]" id="inputOwner" class="form-control" data-team-owner>
            </select>
        </div>

        {{--Banks--}}
        <div class="form-group col-md-8 {{ hasErrors('bank', $errors) }}">
            <label class="control-label" for="inputBank">Bank</label>
            <select name="bank" id="inputBank" class="form-control" data-chosen>
             <option selected disabled>Select bank</option>
                @foreach($banks as $bank)
                    <option value="{{ $bank->id}}">{{ $bank->name }}</option>
                @endforeach
            </select>
        </div>

        {{--Members--}}
        <div class="form-group col-md-6 {{ hasErrors('members', $errors) }}">
            <label class="control-label" for="inputMembers">Members</label>
            <select name="members[]" id="inputMembers" class="form-control" data-nonmembers multiple disabled></select>
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <a href="{{ route('admin.v1.teams.show', $team->id) }}" class="btn btn-default">Back</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection
