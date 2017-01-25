<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests\Admin\V1\StoreFeedbackQuestionRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateFeedbackQuestionRequest;
use Whatsloan\Jobs\StoreFeedbackQuestionJob;
use Whatsloan\Jobs\UpdateFeedbackQuestionJob;
use Whatsloan\Repositories\FeedbackCategories\FeedbackCategory;
use Whatsloan\Repositories\Feedbacks\Feedback;

class FeedbackQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $feedback_questions = Feedback::withTrashed()->with('feedbackcategoryTrashed')->orderBy('deleted_at', 'asc')->paginate();
        return view('admin.v1.feedback.question.index')->withFeedbackQuestions($feedback_questions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = FeedbackCategory::get();
        return view('admin.v1.feedback.question.create')->withCategories($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreFeedbackQuestionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFeedbackQuestionRequest $request)
    {
        $request->offsetSet('uuid', uuid());
        $this->dispatch(new StoreFeedbackQuestionJob($request->all()));
        return redirect()->route('admin.v1.feedback.question.index')->withSuccess('Feedback Question added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $feedback   = Feedback::with('feedbackcategory')->find($id);
        $categories = FeedbackCategory::get();
        return view('admin.v1.feedback.question.edit')->withFeedback($feedback)->withCategories($categories);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|UpdateFeedbackQuestionRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFeedbackQuestionRequest $request, $id)
    {
        $this->dispatch(new UpdateFeedbackQuestionJob($request->all(), $id));
        return redirect()->route('admin.v1.feedback.question.index')->withSuccess('Feedback Question updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $feedback = Feedback::withTrashed()->find($id);
        $feedback->trashed() ? $feedback->restore() : $feedback->delete();
        return redirect()->route('admin.v1.feedback.question.index')->withSuccess('Feedback updated successfully');
    }
}
