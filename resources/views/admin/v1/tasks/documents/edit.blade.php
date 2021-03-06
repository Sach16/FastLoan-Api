@extends('admin.v1.layouts.master')

@section('main')
    <h1>Edit task document</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form
            enctype="multipart/form-data"
            action="{{ route('admin.v1.tasks.documents.update', [
                'banks' => $task->id, 'attachments' => $attachment->id
            ]) }}"
            class="form-horizontal" method="post">
        {{ csrf_field() }}
        {{ method_field('put') }}

        {{--Document Name--}}
        <div class="form-group col-md-9 {{ hasErrors('name', $errors) }}">
            <label class="control-label" for="inputName">Document Name</label>
            <input class="form-control" id="inputName" type="text"
                   name="name"
                   value="{{ $attachment->name }}">
        </div>

        {{--Attachment--}}
        <div class="form-group col-md-9 {{ hasErrors('document', $errors) }}">
            <label class="control-label" for="inputDocument">Document</label>
            <span class="help-block">
                <strong><a href="{{ uploaded($attachment->uri) }}" target="_blank">View current document</a></strong>
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
