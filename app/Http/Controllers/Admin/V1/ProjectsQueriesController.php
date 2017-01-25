<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests\Admin\V1\StoreProjectQueryRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateProjectQueryRequest;
use Whatsloan\Jobs\StoreProjectQueryAsAdminJob;
use Whatsloan\Jobs\UpdateProjectQueryJob;
use Whatsloan\Repositories\Projects\Project;
use Whatsloan\Repositories\Queries\Query;

class ProjectsQueriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $project = Project::with(['queries' => function($query) {
            $query->orderBy('updated_at', 'desc');
        }, 'queries.assignee'])->find($id);
        return view('admin.v1.projects.queries.index')->withProject($project);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $project = Project::find($id);
        return view('admin.v1.projects.queries.create')->withProject($project);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreProjectQueryRequest $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectQueryRequest $request, $id)
    {
        $request->offsetSet('project_id', $id);
        $this->dispatch(new StoreProjectQueryAsAdminJob($request->all()));

        return redirect()->route('admin.v1.projects.queries.index', $id)->withSuccess('Successfully added query');
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
     * @param $projectId
     * @param $queryId
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function edit($projectId, $queryId)
    {
        $query = Query::find($queryId);
        $project = Project::find($projectId);
        return view('admin.v1.projects.queries.edit')
                    ->withProject($project)
                    ->withQuery($query);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|UpdateProjectQueryRequest $request
     * @param $projectId
     * @param $queryId
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(UpdateProjectQueryRequest $request, $projectId, $queryId)
    {
        $request->offsetSet('project_id', $projectId);
        $this->dispatch(new UpdateProjectQueryJob($request->all(), $queryId));

        return redirect()->route('admin.v1.projects.queries.index', $projectId)->withSuccess('Query updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $projectId
     * @param $queryId
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy($projectId, $queryId)
    {
        $query = Query::find($queryId);
        $query->delete();

        return redirect()->back()->withSuccess('Query deleted successfully');
    }
}
