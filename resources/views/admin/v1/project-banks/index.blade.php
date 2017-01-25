@extends('admin.v1.layouts.master')

@section('main')
    <h1>Lead sources</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @forelse($sources as $source)
            <tr>
                <td>{{ $source->name }}</td>
                <td><a href="{{ route('admin.v1.sources.edit', $source->id) }}"
                       class="btn btn-xs btn-warning">Edit</a></td>
                <td>
                    <form action="{{ route('admin.v1.sources.destroy', $source->id) }}"
                          class="form-horizontal inline-block" method="post">
                        {{ csrf_field() }}
                        {{ method_field('delete') }}
                        <button class="btn btn-xs btn-danger" data-confirm>Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr class="text-info">
                <td colspan="3">No Sources</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    {{ $sources->render() }}
@endsection
