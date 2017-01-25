<?php

namespace Whatsloan\Http\Controllers\Api\V1;

use Whatsloan\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Repositories\ProjectStatuses\Contract as IProjectStatuses;
use Whatsloan\Http\Transformers\V1\ProjectStatusTransformer;

class ProjectStatusesController extends Controller
{

    /**
     * @var $projectStatuses 
     */
    private $projectStatuses;

    /**
     * Inteface Contract
     * @param ILoans $projectStatuses
     */
    public function __construct(IProjectStatuses $projectStatuses)
    {
        $this->projectStatuses = $projectStatuses;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statuses = $this->projectStatuses->listing();
        return $this->transformCollection($statuses, ProjectStatusTransformer::class);
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
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

}
