@extends('admin.v1.layouts.master')

@section('main')
    <h1>Edit document <small>({{ $customer->present()->name }})</small></h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.customers.documents.update', [
        'customers' => $customer->id, 'documents' => $document->id
    ]) }}" enctype="multipart/form-data" class="form-horizontal" method="post">
        {{ csrf_field() }}
        {{ method_field('put') }}

        {{--Name--}}
        <div class="form-group col-md-8 {{ hasErrors('name', $errors) }}">
            <label class="control-label" for="inputName">Name</label>
            <input type="text" class="form-control" id="inputName" name="name" value="{{ $document->name }}">
        </div>

        {{--Description--}}
        <div class="form-group col-md-8 {{ hasErrors('description', $errors) }}">
            <label class="control-label" for="inputDescription">Description</label>
            <textarea name="description" id="inputDescription" cols="30" rows="5" class="form-control">{{ $document->description }}</textarea>
        </div>

        {{--Attachment--}}
        <div class="form-group col-md-9 {{ hasErrors('document', $errors) }}">
            <label class="control-label" for="inputDocument">Document</label>
            <span class="help-block">
                <strong><a href="{{ uploaded($document->uri) }}" target="_blank">View current document</a></strong>
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
