@extends('admin.v1.layouts.auth')

@section('main')
    @include('admin.v1.layouts.partials._messages')
    <form method="POST" class="form-horizontal" action="{{ route('admin.v1.password.email.post') }}">
        {!! csrf_field() !!}
        {{--Email--}}
        <div class="form-group col-md-12 {{ hasErrors('email', $errors) }}">
            <label class="control-label" for="inputEmail">Email ID</label>
            <input class="form-control" id="inputEmail" type="text"
                   name="email"
                   value="{{ old('email') }}">
        </div>
        {{--Submit--}}
            <button type="reset" class="btn btn-default">Reset</button>
            <button type="submit" class="btn btn-primary">Send Password Reset Link</button>
            <a href="{{ route('admin.v1.auth.login.get') }}" class="pull-right">Back to Login</a>
    </form>
@endsection