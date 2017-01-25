<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests\Admin\V1\StoreFeedbackCategoryRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateFeedbackCategoryRequest;
use Whatsloan\Jobs\StoreFeedbackCategoryJob;
use Whatsloan\Jobs\UpdateFeedbackCategoryJob;
use Whatsloan\Repositories\FeedbackCategories\FeedbackCategory;

class FeedbackCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $feedback_categories = FeedbackCategory::withTrashed()->with('feedbackQuestions')->orderBy('deleted_at', 'asc')->paginate();
        return view('admin.v1.feedback.category.index')->withFeedbackCategories($feedback_categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.v1.feedback.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreFeedbackCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFeedbackCategoryRequest $request)
    {
        $request->offsetSet('uuid', uuid());
        $this->dispatch(new StoreFeedbackCategoryJob($request->all()));
        return redirect()->route('admin.v1.feedback.category.index')->withSuccess('Feedback Category added successfully');
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
        if (\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.banks.index')->withError("Access Restricted");
        }
        $feedback_category = FeedbackCategory::withTrashed()->find($id);
        return view('admin.v1.feedback.category.edit')->withFeedbackCategory($feedback_category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|UpdateFeedbackCategoryRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFeedbackCategoryRequest $request, $id)
    {
        $this->dispatch(new UpdateFeedbackCategoryJob($request->all(), $id));
        return redirect()->route('admin.v1.feedback.category.index')->withSuccess('Feedback Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.banks.index')->withError("Access Restricted");
        }
        $feedback_category = FeedbackCategory::find($id);
        $feedback_category->delete();

        return redirect()->route('admin.v1.feedback.category.index')->withSuccess('Feedback Category deleted successfully');
    }
}
