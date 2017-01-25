<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ trans('admin.common.title') }} - @yield('title', trans('admin.common.subtitle'))</title>
    <meta name="description" content="{{ trans('admin.common.description') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
</head>
<body>
    @include('admin.v1.layouts.partials._browserhappy')
    @include('admin.v1.layouts.partials._menu')
    <div class="container-fluid row">
        <div class="col-md-3">@include('admin.v1.sidebar.index')</div>
        <div class="col-md-9">@yield('main', 'No main section defined')</div>
    </div>
<script src="{{ elixir('js/plugins.js') }}"></script>
<script src="{{ elixir('js/app.js') }}"></script>
</body>
</html>