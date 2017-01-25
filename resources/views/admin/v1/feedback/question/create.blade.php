@extends('admin.v1.layouts.master')

@section('main')
    <h1>Create a new feedback question</h1>
    @include('admin.v1.layouts.partials._messages')
    <hr>
    <form action="{{ route('admin.v1.feedback.question.store') }}" class="form-horizontal" method="post">
        {{ csrf_field() }}

        {{--Feedback Name--}}
        <div class="form-group col-md-8 {{ hasErrors('question', $errors) }}">
            <label class="control-label" for="inputName">Question</label>
            <input type="text" class="form-control" id="inputName" name="question"
                   value="{{ old('question') }}">
        </div>

        {{--Feedback Category--}}
        <div class="form-group col-md-8 {{ hasErrors('category', $errors) }}">
            <label class="control-label" for="inputCategory">Category</label>
            <select class="form-control" id="inputCategory" name="category" data-chosen>
            <option selected="" disabled="">Select category</option>
                @foreach($categories as $category)
                    <option value="{{$category->id}}" {{ isSelected($category->id,[old('category')])}}>{{$category->name}}</option>
                @endforeach
           </select>
        </div>

        {{--Submit--}}
        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <button type="reset" class="btn btn-default">Reset</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
@endsection
