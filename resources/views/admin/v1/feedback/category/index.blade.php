@extends('admin.v1.layouts.master')

@section('main')
    <h1>View all Feedback Category</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Key</th>
            <th>Status</th>
             @if (\Auth::user()->role === 'SUPER_ADMIN')
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($feedback_categories as $feedback_category)
            <tr>
                <td>{{ $feedback_category->name }}</td>
                <td>{{ $feedback_category->key }}</td>
                <td>{{ indexStatus($feedback_category) }}</td>
                @if (\Auth::user()->role === 'SUPER_ADMIN')
                    <td class="pull-right">
                        <a href="{{ route('admin.v1.feedback.category.edit', $feedback_category->id) }}" class="btn btn-xs btn-warning">Edit</a>
                    </td>
                    <td>
                        @if ( $feedback_category->feedbackQuestions->count() <= 0 )
                            <form action="{{ route('admin.v1.feedback.category.destroy', $feedback_category->id) }}" class="form-inline inline-block" method="post">
                                {{ csrf_field() }}
                                {{ method_field('delete') }}
                                <button class="btn btn-xs btn-{{$feedback_category->trashed()? 'danger' : 'success'}}" data-status-confirm>{{$feedback_category->trashed()? 'Enable feedback category' : 'Disable feedback category'}}</button>
                            </form>
                        @else
                            <button type="button" class="btn btn-default btn-xs" data-container="body" data-toggle="popover" data-placement="bottom" data-content="{{ 'Cannot delete feedback category with active category.' }}
                            " data-original-title="" title="">Disable Feedback category
                            </button>
                        @endif
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $feedback_categories->render() }}
@endsection
