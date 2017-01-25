@extends('admin.v1.layouts.master')

@section('main')
    <h1>Bulk upload leads</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <div class="well">
        <p>Download the template file below and fill upto 100 leads. Once done, upload using the form below.</p>
        <ul>
            <li>Maximum 100 records</li>
            <li>Maximum file size of 2MB</li>
            <li>All fields are mandatory</li>
            <li>Ensure there are no empty rows in the file</li>
        </ul>
        <a href="{{ route('admin.v1.leads.bulk.template') }}"
           class="btn btn small btn-primary">
            Download Template
        </a>
    </div>
    <form action="{{ route('admin.v1.leads.bulk.store') }}"
          enctype="multipart/form-data"
          class="form-horizontal" method="post">
        {{ csrf_field() }}
        <div class="form-group">
            {{--Attachment--}}
            <div class="col-md-9 {{ hasErrors('upload', $errors) }}">
                <label class="control-label" for="inputDocument">Upload file</label>
                <input class="form-control" id="inputDocument" type="file"
                       name="upload"
                       value="{{ old('upload') }}">
                <span class="help-block">
                    <small>
                        <em>Only XLSX files. Maximum 100 records. Maximum file size: 2MB</em>
                    </small>
                </span>
            </div>

            {{--Submit--}}
            <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary" data-uploading>Submit</button>
            </div>
        </div>
    </form>
@endsection
