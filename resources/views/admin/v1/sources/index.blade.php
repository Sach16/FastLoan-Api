@extends('admin.v1.layouts.master')

@section('main')
    <h1>Lead sources</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Status</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @forelse($sources as $source)
            <tr>
                <td>{{ $source->name }}</td>
                <td>{{ indexStatus($source) }}</td>
                <td><a href="{{ route('admin.v1.sources.edit', $source->id) }}"
                       class="btn btn-xs btn-warning">Edit</a></td>
                <td>
                @if($source->leads->count() <= 0)
                    <form action="{{ route('admin.v1.sources.destroy', $source->id) }}" class="form-inline inline-block" method="post">
                        {{ csrf_field() }}
                        {{ method_field('delete') }}
                        <button class="btn btn-xs btn-{{$source->trashed()? 'danger' : 'success'}}" data-status-confirm>{{$source->trashed()? 'Enable Source' : 'Disable Source'}}</button>
                    </form>
                @else
                    <button type="button" class="btn btn-default btn-xs" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Cannot delete source with <li>active leads.</li>" data-original-title="" title="">Disable Source
                    </button>
                @endif
                </td>
            </tr>
        @empty
            <tr class="text-info">
                <td colspan="6">No Sources</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    {{ $sources->render() }}
@endsection
