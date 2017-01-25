@extends('admin.v1.layouts.master')

@section('main')
    <h1>Edit feedback question</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.feedback.question.update', $feedback->id) }}" class="form-horizontal" method="post">
        {{ csrf_field() }}
        {{ method_field('put') }}

        {{--Feedback Category Name--}}
        <div class="form-group col-md-8 {{ hasErrors('question', $errors) }}">
            <label class="control-label" for="inputName">Question</label>
            <input type="text" class="form-control" id="inputName" name="question"
                   value="{{ $feedback->feedback }}">
        </div>

        {{--Feedback Category Key--}}
        <div class="form-group col-md-8 {{ hasErrors('category', $errors) }}">
            <label class="control-label" for="inputCategory">Category</label>
            <select class="form-control" id="inputCategory" name="category" data-chosen>
            <option selected="" disabled="">Select category</option>
                @foreach($categories as $category)
                    <option value="{{$category->id}}" {{ isSelected($feedback->category_id,[$category->id])}}>{{$category->name}}</option>
                @endforeach
           </select>
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <a href="{{ route('admin.v1.feedback.question.index') }}" class="btn btn-default" >Back</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
@endsection
