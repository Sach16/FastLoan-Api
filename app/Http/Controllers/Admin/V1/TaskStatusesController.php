<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests\Admin\V1\StoreTaskStatusRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateTaskStatusRequest;
use Whatsloan\Jobs\StoreTaskStatusJob;
use Whatsloan\Jobs\UpdateTaskStatusJob;
use Whatsloan\Repositories\TaskStatuses\TaskStatus;

class TaskStatusesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.tasks.index')->withError("Access Restricted");
        }

        $statuses = TaskStatus::withTrashed()->orderBy('deleted_at', 'asc')->paginate();
        return view('admin.v1.task-statuses.index')->withStatuses($statuses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.tasks.index')->withError("Access Restricted");
        }

        return view('admin.v1.task-statuses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreTaskStatusRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskStatusRequest $request)
    {
        if (\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.tasks.index')->withError("Access Restricted");
        }

        $request->offsetSet('key', strtoupper(str_replace(' ', '_', $request->label)));
        $this->dispatch(new StoreTaskStatusJob($request->all()));

        return redirect()->route('admin.v1.task-statuses.index')->withSuccess('Task status added successfully');
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
            return redirect()->route('admin.v1.tasks.index')->withError("Access Restricted");
        }

        $status = TaskStatus::withTrashed()->find($id);
        return view('admin.v1.task-statuses.edit')->withStatus($status);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|UpdateTaskStatusRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaskStatusRequest $request, $id)
    {
        if (\Auth::user()->role != 'SUPER_ADMIN') {
            return redirect()->route('admin.v1.tasks.index')->withError("Access Restricted");
        }

        $this->dispatch(new UpdateTaskStatusJob($request->all(), $id));

        return redirect()->route('admin.v1.task-statuses.index')->withSuccess('Task status updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = TaskStatus::withTrashed()->find($id);
        $status->trashed() ? $status->restore() : $status->delete();
        return redirect()->route('admin.v1.task-statuses.index')->withSuccess('Task status updated successfully');
    }
}
