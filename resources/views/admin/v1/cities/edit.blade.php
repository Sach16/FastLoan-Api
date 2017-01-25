@extends('admin.v1.layouts.master')

@section('main')
    <h1>Edit city</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.cities.update', $city->id) }}" class="form-horizontal" method="post">
        {{ csrf_field() }}
        {{ method_field('put') }}

        {{--City Name--}}
        <div class="form-group col-md-6 {{ hasErrors('name', $errors) }}">
            <label class="control-label" for="inputCityName">City Name</label>
            <input type="text" class="form-control" id="inputCityName" name="name"
                   value="{{ $city->name }}">
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <a href="{{ route('admin.v1.cities.index') }}" class="btn btn-default" >Back</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection