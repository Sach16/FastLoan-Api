@extends('admin.v1.layouts.master')

@section('main')
    <h1>View audit logs</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <table class="table table-striped table-condensed">
        <thead>
        <tr>
            <th>User</th>
            <th>Table</th>
            <th>Field</th>
            <th>Timestamp</th>
            <th>Old State</th>
            <th>New State</th>
            <th>Type</th>
        </tr>
        </thead>
        <tbody>
        @forelse($revisions as $revision)
            {{--New Record--}}
            @if(empty(array_diff($revision->state->getOriginal(), $revision->state->getAttributes())))
                @foreach($revision->state->getAttributes() as $name => $record)
                    <tr>
                        <td>{{ $revision->by }}</td>
                        <td>{{ $revision->state->getTable() }}</td>
                        <td>{{ $name }}</td>
                        <td>{{ $revision->created_at->format('d-m-Y H:i:s') }}</td>
                        <td><p>{{ $revision->state->getAttributes()[$name] }}</p></td>
                        <td><p>{{ $revision->state->getAttributes()[$name] }}</p></td>
                        <td>NEW</td>
                    </tr>
                @endforeach
            @else
                {{--Updated Record--}}
                @foreach(array_diff($revision->state->getOriginal(), $revision->state->getAttributes()) as $name => $record)
                    <tr>
                        <td>{{ $revision->by }}</td>
                        <td>{{ $revision->state->getTable() }}</td>
                        <td>{{ $name }}</td>
                        <td>{{ $revision->created_at->format('d-m-Y H:i:s') }}</td>
                        <td><p>{{ $revision->state->getOriginal()[$name] }}</p></td>
                        <td><p>{{ $revision->state->getAttributes()[$name] }}</p></td>
                        <td>MODIFIED</td>
                    </tr>
                @endforeach
            @endif
        @empty
            <tr>
                <td colspan="5">No audit logs available</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    {!! $revisions->render() !!}
@endsection