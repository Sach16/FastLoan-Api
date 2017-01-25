@extends('admin.v1.layouts.auth')

@section('main')
    @include('admin.v1.layouts.partials._messages')
    <form method="POST" class="form-horizontal" action="{{ route('admin.v1.password.reset.post') }}">
        {!! csrf_field() !!}
        <input type="hidden" name="token" value="{{ $token }}">
        {{--Email--}}
        <div class="form-group col-md-12 {{ hasErrors('email', $errors) }}">
            <label class="control-label" for="inputEmail">Email ID</label>
            <input class="form-control" id="inputEmail" type="text"
                   name="email"
                   value="{{ $email }}">
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
            <button type="submit" class="btn btn-primary">Reset Password</button>
    </form>
@endsection