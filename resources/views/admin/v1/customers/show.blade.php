@extends('admin.v1.layouts.master')

@section('main')
    <div class="media">
        <div class="media-left">
            @if( ! $customer->attachments->isEmpty())
            <div class="media-left">
                <a href="{{ uploaded($customer->attachments->first()->uri) }}" target="_blank">
                    <img class="media-object img-rounded"
                         style="width:230px;"
                         src="{{ uploaded($customer->attachments->first()->uri) }}"
                         alt="{{ $customer->present()->name }}">
                </a>
            </div>
            @endif
        </div>
        <div class="media-body">
            <h1>{{ $customer->present()->name }}</h1>
            @include('admin.v1.layouts.partials._messages')
            <hr>
            <strong>Email ID</strong>: {{ $customer->email }}
            <br>
            <strong>Phone Number</strong>: {{ $customer->phone }}
        </div>
    </div>
    <br>
    @if(!empty($customer->settings))
        @foreach($customer->settings as $name => $value)
            <strong>{{ s2l($name) }}</strong>:
            <small><em>{{ s2l($value) }}</em></small>
        @endforeach
    @endif
    <hr>
    <a href="{{ route('admin.v1.customers.edit', $customer->id) }}" class="btn btn-xs btn-info">Edit</a>
    @if($customer->loans->count() <=0 )
        <form action="{{ route('admin.v1.customers.destroy', $customer->id) }}" class="form-inline inline-block" method="post">
            {{ csrf_field() }}
            {{ method_field('delete') }}
            <button class="btn btn-xs btn-{{$customer->trashed()? 'danger' : 'success'}}" data-status-confirm>{{$customer->trashed()? 'Enable Customer' : 'Disable Customer'}}</button>
        </form>
    @else
        <button type="button" class="btn btn-default btn-xs" data-container="body" data-toggle="popover" data-placement="bottom" data-content="{{ 'Cannot delete customer with active loan(s).' }}
        " data-original-title="" title="">Disable Customer
        </button>
    @endif
    <a href="{{ route('admin.v1.customers.documents.index', $customer->id) }}" class="btn btn-xs btn-primary">Documents</a>
    <a href="{{ route('admin.v1.customers.loans.create', $customer->id) }}" class="btn btn-xs btn-primary">Add new loan</a>
    <h3>Loans</h3>
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th>Type</th>
            <th>Agent</th>
            <th>Bank</th>
            <th>Amount</th>
            <th>Status</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @foreach($customer->loansTrashed as $loan)
            <tr>
                <td>{{ $loan->type->name }}</td>
                @if($loan->agent)
                    <td>{{ $loan->agent->present()->name }}</td>
                    @if(!$loan->agent->banks->isEmpty())
                        <td>{{ $loan->agent->banks->first()->name }}</td>
                    @else
                        <td></td>
                    @endif
                @else
                    <td></td>
                    <td></td>
                @endif
                <td>Rs. {{ $loan->present()->amountAsCurrency }}</td>
                <td>{{ indexStatus($loan) }}</td>
                <td><a href="{{ route('admin.v1.loans.show', $loan->id) }}" class="btn btn-xs btn-info">
                    Details
                </a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
