@extends('admin.v1.layouts.master')

@section('main')
    <?php
$BANK_PICTURE = $bank->attachments->filter(function ($item) {
    return ($item->type == 'BANK_PICTURE' ? true : false);
});
$CHECKLIST = $bank->attachments->filter(function ($item) {
    return ($item->type == 'CHECKLIST' ? true : false);
});?>
    <div class="media">
      @if( ! $BANK_PICTURE->isEmpty())
      <div class="media-left">
          <a href="{{ uploaded($BANK_PICTURE->first()->uri) }}" target="_blank">
              <img class="media-object img-rounded"
                   src="{{ uploaded($BANK_PICTURE->first()->uri) }}"
                   alt="{{ $bank->branch }}">
          </a>
      </div>
      @endif
      <div class="media-body">
        <h1>{{ $bank->name }}</h1>
        @include('admin.v1.layouts.partials._messages')
        <small><strong>Branch</strong>: {{ $bank->branch }}</small> |
        <small> <strong>IFSC</strong>: {{ $bank->ifsc_code }}</small>
      </div>
    </div>
    <br>
    <small><strong>Address</strong>: {{ $bank->present()->address }}</small>
    <br><br>
    <div class="row col-md-12">

        @if (\Auth::user()->role === 'SUPER_ADMIN')
        <a href="{{ route('admin.v1.banks.edit', $bank->id) }}" class="btn btn-xs btn-info">Edit bank</a>
        @endif

        <a href="{{ route('admin.v1.banks.projects.index', $bank->id) }}" class="btn btn-xs btn-info">
            Project Approvals
        </a>

        @if (\Auth::user()->role === 'SUPER_ADMIN' && ($bank->projects->count() <= 0) && ($bank->products->count() <= 0) && ($bank->users->count() <= 0) )
                <form action="{{ route('admin.v1.banks.destroy', $bank->id) }}" class="form-inline inline-block" method="post">
                    {{ csrf_field() }}
                    {{ method_field('delete') }}
                    <button class="btn btn-xs btn-{{$bank->trashed()? 'danger' : 'success'}}" data-status-confirm>{{$bank->trashed()? 'Enable Bank' : 'Disable Bank'}}</button>
                </form>
        @else
            <button type="button" class="btn btn-default btn-xs" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Cannot delete banks with {{ $bank->projects->count() > 0 ? '<li> active projects.</li>':'' }}
            {{ $bank->products->count() > 0 ? '<li> active products.</li>':'' }}
            {{ $bank->users->count() > 0 ? '<li> active users.</li>':'' }}" data-original-title="" title="">Disable Bank
            </button>
        @endif
        <br><hr>
    </div>
    <h3>Checklist Documents</h3>
    @if (\Auth::user()->role === 'SUPER_ADMIN')
      <div class="row col-md-12">
          <a href="{{ route('admin.v1.banks.documents.create', $bank->id) }}" class="btn btn-xs btn-primary">Add new document</a>
      </div>
    @endif
    <br>
    <div class="row col-md-12">
        @foreach($CHECKLIST as $attachment)
            <div class="media">
                <div class="media-left">
                    <a href="{{ uploaded($attachment->uri) }}" target="_blank">
                        <i class="glyphicon glyphicon-file"></i>
                    </a>
                </div>
                <div class="media-body">
                    <h4 class="media-heading">{{ $attachment->name }}</h4>
                    {{--Escaped when entering to database--}}
                    <p>{!! nl2br($attachment->description) !!}</p>
                    <a href="{{ uploaded($attachment->uri) }}" target="_blank" class="btn btn-xs btn-info">View</a>
                    @if (\Auth::user()->role === 'SUPER_ADMIN')
                        <a href="{{ route('admin.v1.banks.documents.edit', [
                                    'banks' => $bank->id, 'documents' => $attachment->id
                                ]) }}"
                           class="btn btn-xs btn-warning">Edit</a>
                        <form
                                action="{{ route('admin.v1.banks.documents.destroy',
                                    ['banks' => $bank->id, 'documents' => $attachment->id]) }}"
                                class="form-horizontal inline-block" method="post"
                        >
                            {{ csrf_field() }}
                            {{ method_field('delete') }}
                            <button type="submit" class="btn btn-xs btn-danger" data-confirm>Delete</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
        <hr>
    </div>

    <h3>Products Documents</h3>
      <div class="row col-md-12">
      @if (\Auth::user()->role === 'SUPER_ADMIN')
          <a href="{{ route('admin.v1.banks.products.create', $bank->id) }}" class="btn btn-xs btn-primary">Add new product document</a>
      @endif
      </div>
        <div class="row col-md-12">
        @foreach($products as $product)
        @foreach($product->attachments as $attachment)
            <div class="media">
                <div class="media-left">
                    <a href="{{ uploaded($attachment->uri) }}" target="_blank">
                        <i class="glyphicon glyphicon-file"></i>
                    </a>
                </div>
                <div class="media-body">
                    <h4 class="media-heading">{{ $attachment->name }}</h4>
                    {{--Escaped when entering to database--}}
                    <p>{!! nl2br($attachment->description) !!}</p>
                    <a href="{{ uploaded($attachment->uri) }}" target="_blank" class="btn btn-xs btn-info">View</a>
                    @if (\Auth::user()->role === 'SUPER_ADMIN')
                         <a href="{{ route('admin.v1.banks.products.edit', [
                                    'banks' => $bank->id, 'documents' => $attachment->id
                                ]) }}"
                           class="btn btn-xs btn-warning">Edit</a>
                        <form
                                action="{{ route('admin.v1.banks.products.destroy',
                                    ['banks' => $bank->id, 'documents' => $attachment->id]) }}"
                                class="form-horizontal inline-block" method="post"
                        >
                            {{ csrf_field() }}
                            {{ method_field('delete') }}
                            <button type="submit" class="btn btn-xs btn-danger" data-confirm>Delete</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
        @endforeach
    </div>
@endsection
