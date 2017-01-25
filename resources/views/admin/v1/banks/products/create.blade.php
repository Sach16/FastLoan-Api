@extends('admin.v1.layouts.master')

@section('main')
    <h1>Add a new product</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form
            enctype="multipart/form-data"
            action="{{ route('admin.v1.banks.products.store', $id) }}"
            class="form-horizontal" method="post">
        {{ csrf_field() }}

        {{--Product Type--}}
        <div class="form-group col-md-9 {{ hasErrors('product', $errors) }}">
            <label class="control-label" for="inputProduct">Product</label>
            <select name="product" id="inputProduct" class="form-control" data-loan-types></select>
        </div>

        {{--Document Name--}}
        <div class="form-group col-md-9 {{ hasErrors('name', $errors) }}">
            <label class="control-label" for="inputName">Document Name</label>
            <input class="form-control" id="inputName" type="text"
                   name="name"
                   value="{{ old('name') }}">
        </div>

        {{--Document Description--}}
        <div class="form-group col-md-9 {{ hasErrors('description', $errors) }}">
            <label class="control-label" for="inputDescription">Description</label>
            <textarea name="description" id="inputDescription" cols="30" rows="10"
                      class="form-control">{{ old('description') }}</textarea>
        </div>

        {{--Attachment--}}
        <div class="form-group col-md-9 {{ hasErrors('document', $errors) }}">
            <label class="control-label" for="inputDocument">Document</label>
            <input class="form-control" id="inputDocument" type="file"
                   name="document"
                   value="{{ old('document') }}">
            <span class="help-block">
                <small>
                    <em>Only PDF and Image files. Maximum file size: 10MB</em>
                </small>
            </span>
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <button type="reset" class="btn btn-default">Reset</button>
                <button type="submit" class="btn btn-primary" data-uploading>Submit</button>
            </div>
        </div>
    </form>
@endsection
