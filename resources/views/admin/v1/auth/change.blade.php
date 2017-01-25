@extends('admin.v1.layouts.master')

@section('main')
    <h1>Change Password</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form method="POST" class="form-horizontal" action="{{ route('admin.v1.password.update.post') }}">
        {!! csrf_field() !!}
        {{--Old Password--}}
        <div class="form-group col-md-12 {{ hasErrors('old_password', $errors) }}">
            <label class="control-label" for="inputOldPassword">Old Password</label>
            <input class="form-control" id="inputOldPassword" type="password"
                   name="old_password" >
        </div>

        {{--Password--}}
        <div class="form-group col-md-12 {{ hasErrors('password', $errors) }}">
            <label class="control-label" for="inputPassword">New Password</label>
            <input class="form-control" id="inputPassword" type="password"
                   name="password" >
        </div>
        {{--Confirm Password--}}
        <div class="form-group col-md-12 {{ hasErrors('password_confirmation', $errors) }}">
            <label class="control-label" for="inputConfirmPassword">Confirm New Password</label>
            <input class="form-control" id="inputConfirmPassword" type="password"
                   name="password_confirmation" >
        </div>
        {{--Submit--}}
            <button type="reset" class="btn btn-default">Cancel</button>
            <button type="submit" class="btn btn-primary">Change Password</button>
    </form>
@endsection