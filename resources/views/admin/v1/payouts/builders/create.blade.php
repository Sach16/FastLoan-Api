@extends('admin.v1.layouts.master')
@section('main')
<h1>Add new builder payout</h1>
@include('admin.v1.layouts.partials._messages')
<hr>
<form action="{{ route('admin.v1.payouts.builder.store') }}" class="form-horizontal" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="role" value="CONSUMER">

    {{-- Builders --}}
    <div class="form-group col-md-8 {{ hasErrors('builder_id', $errors) }}">
        <label class="control-label" for="source_id_selected">Builders</label>
        <input type="hidden" value="{{old('builder_id') }}" id="selected-builders">
        <select name="builder_id" id="inputBuilder" class="form-control" data-builders></select>
    </div>

    {{-- Projects --}}
    <div class="form-group col-md-8 {{ hasErrors('project_id', $errors) }} ">
        <label class="control-label" for="inputPayoutProject">Projects</label>
        <input type="hidden" value="{{ old('project_id') }}" id="selected-projects">
        <select name="project_id" id="inputPayoutProject" class="form-control" data-payout-projects></select>
    </div>

    {{--Percentage--}}
    <div class="form-group col-md-8 {{ hasErrors('percentage', $errors) }}">
        <label class="control-label" for="inputPercentage">Percentage</label>
        <input type="text" class="form-control"
               id="inputPercentage" name="percentage"
               value="{{ old('percentage') }}">
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