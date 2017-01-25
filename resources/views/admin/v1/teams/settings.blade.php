@extends('admin.v1.layouts.master')

@section('main')
    <?php $member = $team->members[0];?>
    <h1>Edit settings of '{{$member->present()->name}}'  (<em>{{ $team->name }}</em>)</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
        <form action="{{ route('admin.v1.teams.edit.settings.update', [$team->id,$member->present()->id]) }}" class="form-horizontal" method="post">
        {{ csrf_field() }}

        {{--Target--}}
        <div class="form-group col-md-8 {{ hasErrors('target', $errors) }}">
            <label class="control-label" for="inputTarget">Target</label>
            <input type="text" class="form-control" id="inputTarget" name="target" value="{{ $member->pivot->target }}">
        </div>
        {{--Achieved--}}
        <div class="form-group col-md-8 {{ hasErrors('achieved', $errors) }}">
            <label class="control-label" for="inputAchieved">Achieved</label>
            <input type="text" class="form-control" id="inputAchieved" name="achieved" value="{{ $member->pivot->achieved }}">
        </div>
        {{--Incentive Plan--}}
        <div class="form-group col-md-8 {{ hasErrors('incentive_plan', $errors) }}">
            <label class="control-label" for="inputIncentivePlan">Incentive plan</label>
            <input type="text" class="form-control" id="inputIncentivePlan" name="incentive_plan" value="{{ $member->pivot->incentive_plan }}">
        </div>
        {{--Incentive Earned--}}
        <div class="form-group col-md-8 {{ hasErrors('incentive_earned', $errors) }}">
            <label class="control-label" for="inputIncentiveEarned">Incentive earned</label>
            <input type="text" class="form-control" id="inputIncentiveEarned" name="incentive_earned" value="{{ $member->pivot->incentive_earned }}">
        </div>

        @if($member->pivot->is_owner != 1)
        {{--Reports To--}}
        <div class="form-group col-md-8 {{ hasErrors('reports_to', $errors) }}">
            <label class="control-label" for="inputIncentiveEarned">Reports To</label>
            <select  class="form-control" id="inputIncentiveEarned" name="reports_to[]" multiple data-chosen>
                @foreach($reports_to->members as $member)
                    <option value="{{ $member->id }}"
                            {{ isSelected($member->id, $user->reportsTo->lists('id')->toArray()) }}>
                        {{ $member->present()->name }}
                    </option>
                @endforeach
            </select>
        </div>
        @endif

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <a href="{{ route('admin.v1.teams.show', $team->id) }}" class="btn btn-default">Back</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection