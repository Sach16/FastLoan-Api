@extends('admin.v1.layouts.master')

@section('main')
    <h1>Remove '{{$user->present()->name}}'  (<em>{{ $team->name }}</em>)</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
        <form action="{{ route('admin.v1.teams.remove.owner.from.team', [$team->id,$user->id]) }}" class="form-horizontal" method="post">
        {{ csrf_field() }}

        {{--Reports To--}}
        <div class="form-group col-md-8 {{ hasErrors('assigned_to', $errors) }}">
            <label class="control-label" for="inputIncentiveEarned">Assign customers to another team DSA</label>
            <select  class="form-control" id="inputIncentiveEarned" name="assigned_to" data-chosen>
            <option value='' selected disabled >Select member</option>
            @foreach($teams as $team)
                @foreach($team->members as $member)
                    @if($member->pivot->is_owner)
                        <option value="{{ $member->id }}">{{ $member->present()->name }} - {{ $team->name }}</option>
                    @endif
                @endforeach
            @endforeach
            </select>
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <a href="{{ route('admin.v1.teams.show', $team->id) }}" class="btn btn-default">Back</a>
                <button type="submit" class="btn btn-primary">Remove</button>
            </div>
        </div>
    </form>
@endsection