@extends('admin.v1.layouts.master')

@section('main')
    <h1>Submit for approval</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.projectbanks.store') }}" class="form-horizontal" id="projectBanks" method="post">
        {{ csrf_field() }}


        {{--Agents --}}
        <div class="form-group col-md-8 {{ hasErrors('agent_id', $errors) }}">
            <label class="control-label" for="assigned_to">DSA</label>
            <input type="hidden" value="{{ old('agent_id') }}" id="selected-agent_id">
            <select name="agent_id" id="agentId" class="form-control" value="{{ old('agent_id') }}" data-members-project-approval></select>
        </div>


        {{--Bank--}}
        <div class="form-group col-md-8 {{ hasErrors('bank_id', $errors) }}">
            <label class="control-label" for="inputFirstName">Bank</label>
            <input type="text" class="form-control" id="inputMemberBankDisplay"  value="{{ old('bank_id') }}" disabled>
            <input type="hidden" class="form-control" id="inputMemberBank" name="bank_id" value="{{ old('bank_id') }}">
        </div>

        <input type="hidden" class="form-control" id="inputProjectId" name="project_id" value="{{ $project }}">

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <button type="reset" class="btn btn-default">Reset</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection
