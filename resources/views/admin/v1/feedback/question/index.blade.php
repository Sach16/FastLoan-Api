@extends('admin.v1.layouts.master')

@section('main')
    <h1>View all Feedback Questions</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Category</th>
            <th>Status</th>
             @if (\Auth::user()->role === 'SUPER_ADMIN')
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($feedback_questions as $feedback_question)
            <tr>
                <td>{{ $feedback_question->feedback }}</td>
                <td>{{ $feedback_question->feedbackcategoryTrashed->name }}</td>
                <td>{{ indexStatus($feedback_question) }}</td>
                <td class="pull-right">
                    <a href="{{ route('admin.v1.feedback.question.edit', $feedback_question->id) }}" class="btn btn-xs btn-warning">Edit</a>
                </td>
                <td>
                    @if ( $feedback_question->feedbackcategoryTrashed->count() <= 0 || $feedback_question->feedbackcategoryTrashed->deleted_at )
                        <form action="{{ route('admin.v1.feedback.question.destroy', $feedback_question->id) }}" class="form-inline inline-block" method="post">
                            {{ csrf_field() }}
                            {{ method_field('delete') }}
                            <button class="btn btn-xs btn-{{$feedback_question->trashed()? 'danger' : 'success'}}" data-status-confirm>{{$feedback_question->trashed()? 'Enable feedback question' : 'Disable feedback question'}}</button>
                        </form>
                    @else
                        <button type="button" class="btn btn-default btn-xs" data-container="body" data-toggle="popover" data-placement="bottom" data-content="{{ 'Cannot delete feedback question with active category.' }}
                        " data-original-title="" title="">Disable Feedback Question
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $feedback_questions->render() }}
@endsection
