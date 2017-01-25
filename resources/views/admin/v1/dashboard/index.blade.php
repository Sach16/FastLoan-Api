@extends('admin.v1.layouts.master')

@section('main')
    <div class="jumbotron">
        <h1>Welcome <em>{{ request()->user()->present()->name }}</em></h1>
        <p>
            You have logged in as a
            <em>{{ request()->user()->role }}</em>
            using
            <em>{{ request()->user()->email }}</em>
            at
            {{ $login->created_at->format('d-m-Y H:i:s')  }}
        </p>
    </div>
@endsection
