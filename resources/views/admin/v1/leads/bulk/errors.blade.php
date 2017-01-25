@extends('admin.v1.layouts.master')

@section('main')
    <h1>Upload errors</h1>
    <hr>
    <table class="table table-striped table-condensed">
        <thead>
        <tr>
            <th>Cell</th>
            <th>Message</th>
            <th>Row</th>
        </tr>
        </thead>
        <tbody>
        @foreach($errors->messages() as $key => $error)
            @foreach($error as $message)
                <tr>
                    <td><span class="label label-warning">{{ str_replace('.', ' ', strtoupper($key)) }}</span></td>
                    <td>{{ $message }}</td>
                    <td>{{ $row }}</td>
                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>
@endsection