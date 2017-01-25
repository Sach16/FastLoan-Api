@extends('admin.v1.layouts.master')

@section('main')
    <h1>Transfer phone number</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.users.phone.update', $user->id) }}" class="form-horizontal" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="user_id" value="{{ $user->id }}">

        {{--User--}}
        <div class="form-group col-md-8">
            <label class="control-label" for="inputCountry">User</label>
            <input type="text" readonly class="form-control" value="{{ $user->present()->name }}">
        </div>

        {{--Phone--}}
        <div class="form-group col-md-8 {{ hasErrors('phone', $errors) }}">
            <label class="control-label" for="inputCountry">Phone Number</label>
            <select name="phone" id="inputPhone" class="form-control" data-chosen>
                <option disabled selected>Select phone number</option>
                @foreach($phones as $phone)
                    <option value="{{ $phone->phone }}">{{ $phone->phone }}</option>
                @endforeach
            </select>
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <button type="reset" class="btn btn-default" data-back>Back</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection