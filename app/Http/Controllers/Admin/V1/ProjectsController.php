<?php

namespace Whatsloan\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests\Admin\V1\StoreProjectRequest;
use Whatsloan\Http\Requests\Admin\V1\UpdateProjectRequest;
use Whatsloan\Jobs\StoreProjectImageJob;
use Whatsloan\Jobs\StoreProjectJob;
use Whatsloan\Jobs\UpdateProjectJob;
use Whatsloan\Repositories\ProjectStatuses\ProjectStatus;
use Whatsloan\Repositories\Projects\Project;
use Whatsloan\Repositories\Users\User;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::with(['builderTrashed', 'owner'])->withTrashed()
            ->orderBy('deleted_at', 'asc');

        if (\Auth::user()->role == 'DSA_OWNER') {
            $userTeam = User::with(['teams', 'teams.members'])->find(\Auth::user()->id)->teams()->first();
            $projects = $projects->withTrashed()->whereIn('owner_id', $userTeam->members->lists('id')->all());
        }
        return view('admin.v1.projects.index')->withProjects($projects->paginate());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $statuses = ProjectStatus::all();
        return view('admin.v1.projects.create')->withStatuses($statuses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|StoreProjectRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request)
    {
        $project = $this->dispatch(new StoreProjectJob($request->except(['project_picture'])));
        if ($request->exists('project_picture')) {
            $upload = upload($project->getProjectPicturePath(), $request->file('project_picture'));
            $request->offsetSet('attachment', $upload);
        }
        $this->dispatch(new StoreProjectImageJob($request->except(['project_picture']), $project));
        return redirect()->route('admin.v1.projects.index')->withSuccess('Project added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($this->isAuthorized($id)){
            return redirect()->route('admin.v1.projects.index')->withSuccess('Access Restricted');
        }
        $project = Project::with(['builderTrashed', 'banks', 'addresses', 'addresses.city','loan'])
            ->with(['attachments' => function ($query) {
                $query->where('type', 'PROJECT_PICTURE');
            }])
            ->withTrashed()
            ->find($id);
        return view('admin.v1.projects.show')->withProject($project);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if($this->isAuthorized($id)){
            return redirect()->route('admin.v1.projects.index')->withSuccess('Access Restricted');
        }
        $statuses = ProjectStatus::all();
        $project  = Project::with(['addresses', 'addresses.city', 'owner', 'builder'])
            ->withTrashed()
            ->find($id);
        return view('admin.v1.projects.edit')->withProject($project)->withStatuses($statuses);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request|UpdateProjectRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectRequest $request, $id)
    {
        $project = Project::find($id);
        $request->offsetSet('project_id', $id);
        if ($request->exists('project_picture')) {
            $upload = upload($project->getProjectPicturePath(), $request->file('project_picture'));
            $request->offsetSet('attachment', $upload);
        }
        $this->dispatch(new UpdateProjectJob($request->except(['project_picture'])));
        return redirect()->back()->withSuccess('Project updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::withTrashed()->findOrFail($id);
        $project->trashed() ? $project->restore() : $project->delete();
        return redirect()->route('admin.v1.projects.index')->withSuccess('Project updated successfully');
    }

    /**
     * Authorized the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    private function isAuthorized($id)
    {
        if(\Auth::user()->role <> 'SUPER_ADMIN')
        {
            $userTeam = User::with(['teams', 'teams.members'])->find(\Auth::user()->id)->teams()->first();
            $projects = Project::with(['builder', 'owner'])->withTrashed()
            ->orderBy('deleted_at', 'asc');
            $projects = $projects->whereIn('owner_id', $userTeam->members->lists('id')->all())
                ->lists('id')
                ->all();
                if(!in_array($id,$projects))
                {
                    return true;
                }
        }
    }
}
