@extends('admin.v1.layouts.auth')

@section('main')
    <h1>Whatsloan Admin</h1>
    @include('admin.v1.layouts.partials._messages')
    <form action="{{ route('admin.v1.auth.login.post') }}" class="form-horizontal" method="post">
        {{ csrf_field() }}
        
        {{--Phone Number--}}
        
         <div class="form-group col-md-12">
            <label class="control-label" for="inputEmail">Phone Number</label>
            <input class="form-control" id="inputPhone" type="text"
                   name="phone"
                   value="{{ old('phone') }}">
        </div>

        {{--Password--}}
        <div class="form-group col-md-12">
            <label class="control-label" for="inputPassword">Password</label>
            <input class="form-control" id="inputPassword" type="password"
                   name="password">
            <span class="help-block">
                <small>
                    <em>Forgot your password? <a href="{{ route('admin.v1.password.email.get') }}">Click here to continue</a>.</em>
                </small>
            </span>
        </div>

        {{--Submit--}}
        <button type="reset" class="btn btn-default">Cancel</button>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection