@extends('admin.v1.layouts.master')

@section('main')
    <h1>Add new product</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.products.store') }}" class="form-horizontal" method="post">
        {{ csrf_field() }}

        {{--Name--}}
        <div class="form-group col-md-8 {{ hasErrors('name', $errors) }}">
            <label class="control-label" for="inputProductName">Name</label>
            <input type="text" class="form-control" id="inputProductName" name="name" value="{{ old('name') }}">
        </div>

        {{--Key--}}
        <div class="form-group col-md-8 {{ hasErrors('key', $errors) }}">
            <label class="control-label" for="inputProductKey">Key</label>
            <input type="text" class="form-control" id="inputProductKey" name="key" value="{{ old('key') }}">
        </div>

        {{--Description--}}
        <div class="form-group col-md-8 {{ hasErrors('description', $errors) }}">
            <label class="control-label" for="inputProductDescription">Description</label>
            <textarea name="description" id="inputProductDescription" cols="30" rows="5" class="form-control">{{ old('description') }}</textarea>
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <button type="reset" class="btn btn-default">Reset</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection
