<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;

use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Requests\Admin\V1\StoreTaskStageRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateTaskStageRequest;
use Whatsloan\Jobs\StoreTaskStageJob;
use Whatsloan\Jobs\UpdateTaskStageJob;
use Whatsloan\Repositories\TaskStages\TaskStage;

class TaskStagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.tasks.index')->withError("Access Restricted");
        }
        
        $stages = TaskStage::with('tasks')->withTrashed()->orderBy('created_at', 'asc')->paginate();
        return view('admin.v1.task-stages.index')->withStages($stages);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.tasks.index')->withError("Access Restricted");
        }
        return view('admin.v1.task-stages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreTaskStageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskStageRequest $request)
    {
        if(\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.tasks.index')->withError("Access Restricted");
        }
        
        $request->offsetSet('key', strtoupper(str_replace(' ', '_', $request->label)));
        $this->dispatch(new StoreTaskStageJob($request->all()));

        return redirect()->route('admin.v1.task-stages.index')->withSuccess('Task stage added successfully');
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
        if(\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.tasks.index')->withError("Access Restricted");
        }
        
        $stage = TaskStage::withTrashed()->find($id);
        return view('admin.v1.task-stages.edit')->withStage($stage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|UpdateTaskStageRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaskStageRequest $request, $id)
    {
        if(\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.tasks.index')->withError("Access Restricted");
        }
        
        $this->dispatch(new UpdateTaskStageJob($request->all(), $id));

        return redirect()->route('admin.v1.task-stages.index')->withSuccess('Task stage updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task_stage = TaskStage::withTrashed()->find($id);
        $task_stage->trashed() ? $task_stage->restore() : $task_stage->delete();
        return redirect()->route('admin.v1.task-stages.index')->withSuccess('Task stage updated successfully');
    }
}
