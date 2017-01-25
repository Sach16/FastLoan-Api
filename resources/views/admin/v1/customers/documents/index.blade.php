@extends('admin.v1.layouts.master')

@section('main')
    <h1>Customer Documents <small>({{ $customer->present()->name }})</small></h1>
    @include('admin.v1.layouts.partials._messages')
    <a href="{{ route('admin.v1.customers.documents.create', $customer->id) }}" class="btn btn-xs btn-primary">
        Add new document
    </a>
    @if (\Auth::user()->role === 'SUPER_ADMIN')
      <a href="{{ route('admin.v1.customers.download', $customer->id) }}" class="btn btn-xs btn-info">Extract documents</a>
    @endif
    <hr>
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @forelse($customer->attachments as $document)
        <tr>
            <td>{{ $document->name }}</td>
            <td>{{ $document->description }}</td>
            <td>
                <a class="btn btn-xs btn-info" href="{{ uploaded($document->uri) }}" target="_blank">View</a>
            </td>
            <td>
                <a href="{{ route('admin.v1.customers.documents.edit', [
                    'customers' => $customer->id, 'documents' => $document->id
                ]) }}" class="btn btn-xs btn-warning">
                    Edit
                </a>
            </td>
            <td>
                <form action="{{ route('admin.v1.customers.documents.destroy', [
                    'customers' => $customer->id, 'documents' => $document->id
                ]) }}" class="form-horizontal inline-block" method="post">
                    {{ csrf_field() }}
                    {{ method_field('delete') }}
                    <button class="btn btn-xs btn-danger" type="submit" data-confirm>Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5">No Documents</td>
        </tr>
        @endforelse
        </tbody>
    </table>
@endsection
