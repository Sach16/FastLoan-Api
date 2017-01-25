<?php

namespace Whatsloan\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Repositories\Banks\Bank;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Repositories\Projects\Project;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Jobs\UpdateProjectApiJob;

use Whatsloan\Http\Transformers\V1\ResourceCreated;
use Whatsloan\Http\Transformers\V1\ResourceUpdated;
use Whatsloan\Http\Transformers\V1\BanksProjectsTransformer;
use Whatsloan\Http\Transformers\V1\BankTransformer;

use Whatsloan\Repositories\Banks\Contract as Banks;
use Whatsloan\Http\Requests\Api\V1\UpdateBankProjectRequest;

class BanksProjectsController extends Controller
{
    
    
    protected $banks;
    
    public function __construct(Banks $banks)
    {
        $this->banks = $banks;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        
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
     * @param  \Illuminate\Http\Request $request
     * @param $bankId
     * @param $projectId
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBankProjectRequest $request, $bankUuid, $projectUuid)
    {
       $requestData = $request->all();
       $bankId = Bank::whereUuid($bankUuid)->first()->id;
       $projectId = Project::whereUuid($projectUuid)->first()->id;
       
       if(isset($requestData['assignee'])) {
           $requestData['agent_id'] = User::whereUuid($request['assignee'])->first()->id;
           $requestData['bank_id'] = Bank::whereUuid($request['bank_uuid'])->first()->id;
       }
       
       $bankProject = $this->dispatch(new UpdateProjectApiJob($requestData, $bankId, $projectId));
       return $this->transformItem($bankProject, ResourceUpdated::class);
        
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
    
    
    /**
     * Get approved project list
     */
    public function approved() {
        $banks = $this->banks->approvalRequests('APPROVED');
        return $this->transformCollection($banks, new BanksProjectsTransformer);
    }
    
    
    
    /**
     * Get approved project list
     */
    public function pending() {
        $banks = $this->banks->approvalRequests('PENDING');
        return $this->transformCollection($banks, new BanksProjectsTransformer);
    }
    
    
    /**
     * Returns aproving banks list
     * @return Collection
     */
    public function approvedBy()
    {
        $banks = $this->banks->approvedBy(request()->all());
        return $this->transformCollection($banks, new BankTransformer);
    }
    
}
