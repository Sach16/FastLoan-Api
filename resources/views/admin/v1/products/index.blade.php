@extends('admin.v1.layouts.master')

@section('main')
    <h1>View all products</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Key</th>
            <th>Description</th>
            <th>Status</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @forelse($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->key }}</td>
                <td>{{ $product->description }}</td>
                <td>{{ indexStatus($product) }}</td>
                <td><a href="{{ route('admin.v1.products.edit', $product->id) }}"
                       class="btn btn-xs btn-warning">Edit</a></td>
               @if( $product->banks->count() <= 0 && $product->loans->count() <= 0 )
                    <td>
                        <form action="{{ route('admin.v1.products.destroy', $product->id) }}" class="form-inline inline-block" method="post">
                            {{ csrf_field() }}
                            {{ method_field('delete') }}
                            <button class="btn btn-xs btn-{{$product->trashed()? 'danger' : 'success'}}" data-status-confirm>{{$product->trashed()? 'Enable product' : 'Disable product'}}</button>
                        </form>
                    </td>
                @else
                    <td>
                        <button type="button" class="btn btn-default btn-xs" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Cannot delete product with 
                            {{ $product->banks->count() > 0 ? '<li>active banks.</li>' : ''}}
                            {{ $product->loans->count() > 0 ? '<li>active loans.</li>' : ''}}
                        " data-original-title="" title="">Disable product
                        </button>
                    </td>
                @endif
            </tr>
        @empty
            <tr class="text-info">
                <td colspan="6">No products</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    {{ $products->render() }}
@endsection
