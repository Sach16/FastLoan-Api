<?php

namespace Whatsloan\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Repositories\Projects\Contract as Projects;
use Whatsloan\Repositories\Banks\Contract as Banks;
use Whatsloan\Repositories\Cities\Contract as Cities;
use Whatsloan\Repositories\Teams\Contract as Teams;
use Whatsloan\Http\Transformers\V1\ProjectTransformer;
use Whatsloan\Http\Transformers\V1\BankTransformer;
use Whatsloan\Http\Transformers\V1\BanksApprovedProjectsTransformer;
use Whatsloan\Http\Requests\Api\V1\StoreProjectRequest;
use Whatsloan\Http\Transformers\V1\ResourceCreated;
use Whatsloan\Http\Transformers\V1\ValidationError;
use Whatsloan\Jobs\StoreProjectImageJob;

class ProjectsController extends Controller
{

    /**
     * @var $projects
     */
    private $projects;

    /**
     * @var $projects
     */
    private $banks;

    /**
     * @var $cities
     */
    private $cities;

    /**
     * @var $teams
     */
    private $teams;

    /**
     * Repository controller constructor
     * @param Projects $projects
     */
    public function __construct(Projects $projects, Banks $banks, Cities $cities, Teams $teams)
    {
        $this->projects = $projects;
        $this->banks = $banks;
        $this->cities = $cities;
        $this->teams = $teams;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $paginate = ($request->get('paginate') == 'all') ? 100 : $request->get('paginate');
        $projects = $this->projects->approved($request->all(), $paginate);
        return $this->transformCollection($projects, ProjectTransformer::class);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request)
    {

        $data = $request->all();
        if ($request->project_uuid) {

            if ($this->projects->isBankAssociated(\Auth::guard('api')->user()->id, $data['bank_uuid'], $data['project_uuid'])) {
                return $this->transformItem("Bank already associated by the agent", ValidationError::class);
            }

            $project = $this->projects->update($data);
            
            if ($request->exists('project_picture')) {
                $upload = upload($project->getProjectPicturePath(), $request->file('project_picture'));
                $request->offsetSet('attachment', $upload);
            }
            $this->dispatch(new StoreProjectImageJob($request->except(['project_picture']), $project));
            
            return $this->transformItem($project, ProjectTransformer::class);
        } else {
            $project = $this->projects->store($data);
            
            if ($request->exists('project_picture')) {
                $upload = upload($project->getProjectPicturePath(), $request->file('project_picture'));
                $request->offsetSet('attachment', $upload);
            }
            $this->dispatch(new StoreProjectImageJob($request->except(['project_picture']), $project));
            
            return $this->transformItem($project, ResourceCreated::class, 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $project = $this->projects->show($uuid);
        return $this->transformItem($project, ProjectTransformer::class);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function getProjects() 
    {
        $project = $this->projects->getProjectsLists(request()->all());
        return $this->transformCollection($project, ProjectTransformer::class);        
    }

}
