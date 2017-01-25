@extends('admin.v1.layouts.master')

@section('main')
    <h1>{{ $builder->name }}</h1>
    @include('admin.v1.layouts.partials._messages')
    <small><strong>Address</strong>: {{ $builder->addresses->first()->present()->address }}</small>
    <hr>
    <a href="{{ route('admin.v1.builders.edit', $builder->id) }}" class="btn btn-xs btn-primary">Edit</a>
    @if($can_delete)
        <form action="{{ route('admin.v1.builders.destroy', $builder->id) }}" class="form-inline inline-block" method="post">
            {{ csrf_field() }}
            {{ method_field('delete') }}
            <button class="btn btn-xs btn-{{$builder->trashed()? 'danger' : 'success'}}" data-status-confirm>{{$builder->trashed()? 'Enable Builder' : 'Disable Builder'}}</button>
        </form>
    @else
        <button type="button" class="btn btn-default btn-xs" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Cannot delete builder with <li>active projects.</li>" data-original-title="" title="">Disable Builder
        </button>
    @endif
    <br>
    <h3>Projects</h3>
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Address</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @foreach($builder->projects as $project)
        <tr>
            <td>{{ $project->name }}</td>
            <td>{{ $project->addresses()->first()->present()->address }}</td>
            <td><a href="{{ route('admin.v1.projects.show', $project->id) }}" class="btn btn-xs btn-info">Details</a></td>
        </tr>
        @endforeach
        </tbody>
    </table>
@endsection