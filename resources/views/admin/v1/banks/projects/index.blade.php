@extends('admin.v1.layouts.master')

@section('main')
    <h1>Project Approvals <small>({{ $bank->name }})</small></h1>
    @include('admin.v1.layouts.partials._messages')
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th>Project Name</th>
            <th>Builder Name</th>
            <th>Units</th>
            <th>Possession Date</th>
            <th>Agent</th>
            <th>Approval</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($bank->projects as $project)
            <tr>
                <td>{{ $project->name }}</td>
                <td>{{ $project->builder->name }}</td>
                <td>{{ $project->unit_details }}</td>
                <td>{{ $project->present()->possessionDate }}</td>
                @if($project->pivot->agent)
                    <td>{{ $project->pivot->agent->present()->name }}</td>
                @else
                    <td></td>
                @endif
                <td>
                    <span class="label label-{{ label($project->pivot->status) }}">
                        {{ $project->pivot->status }}
                    </span>
                </td>
                <td>@include('admin.v1.banks.projects.partials._change-status')</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection