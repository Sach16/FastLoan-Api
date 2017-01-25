@extends('admin.v1.layouts.master')

@section('main')
    <h1>Edit builder payout</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.payouts.builder.update', $project->id) }}" class="form-horizontal" method="post">
        {{ csrf_field() }}
        {{ method_field('put') }}


         {{-- Builders --}}
        <div class="form-group col-md-8 {{ hasErrors('builder_id', $errors) }}">
            <label class="control-label" for="builder_id">Builders</label>
            <input type="hidden" value="{{ $project->builder->id }}" id="selected-builders">
            <select name="builder_id" id="inputBuilder" class="form-control" data-builders disabled></select>
        </div>


         {{-- Projects --}}
        <div class="form-group col-md-8 {{ hasErrors('project_id', $errors) }} ">
            <label class="control-label" for="inputProject">Projects</label>
            <input type="hidden" value="{{ $project->id }}" id="selected-projects">
            <input type="hidden" value="{{ $project->id }}" name="project_id">
            <select name="project_id" id="inputProject" class="form-control" data-projects disabled></select>
        </div>

         {{--Percentage--}}
        <div class="form-group col-md-8 {{ hasErrors('percentage', $errors) }}">
            <label class="control-label" for="inputPercentage">Percentage</label>
            <input type="text" class="form-control" id="inputPercentage" name="percentage" value="{{ $project->payouts->first()->percentage }}">
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <a href="{{ route('admin.v1.payouts.index') }}" class="btn btn-default" >Back</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection