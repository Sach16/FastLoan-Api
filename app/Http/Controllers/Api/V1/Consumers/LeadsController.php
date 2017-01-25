<?php

namespace Whatsloan\Http\Controllers\Api\V1\Consumers;

use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Repositories\Leads\Contract as Leads;
use Whatsloan\Repositories\Users\Contract as Users;
use Whatsloan\Http\Transformers\V1\Consumers\LeadTransformer;
use Whatsloan\Http\Transformers\V1\Consumers\LeadValidationFailedTransformer;
use Whatsloan\Http\Requests\Api\V1\StoreLeadRequest;

use Illuminate\Support\Facades\Validator;

//use Whatsloan\Jobs\StoreLeadJob;
//
//
//use Whatsloan\Http\Requests\Api\V1\UpdateLeadRequest;
//use Whatsloan\Http\Transformers\V1\ResourceCreated;


class LeadsController extends Controller
{
    /**
     * @var Leads
     */
    private $leads;
      /**
     * @var Leads
     */
    private $users;

    /**
     * LeadsController constructor
     *
     * @param Leads $leads
     */
    public function __construct(Leads $leads,Users $users)
    {
        $this->leads = $leads;
        $this->users = $users;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authUser = \Auth::guard('api')->user();
        
        $leads = $this->leads->getLeadsByUserIdAsConsumers($authUser->id);
        return $this->transformCollection($leads, LeadTransformer::class);
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
    public function store(StoreLeadRequest $request)
    {
        $data = $request->all();
       
        $lead = $this->leads->add($data);   
        return $this->transformItem($lead, ResourceCreated::class, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $uuid
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $lead = $this->leads->find($uuid);
        return $this->transformItem($lead, LeadTransformer::class);
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
    public function update(UpdateLeadRequest $request, $uuid)
    {
        $data = $request->all();
        $lead = $this->leads->update($data, $uuid);
        return $this->transformItem($lead, ResourceCreated::class, 201);
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
    public function newLead(Request $request) 
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|min:8',
            'email' => 'required',
            'city_uuid' => 'required|exists:cities,uuid',
            'type_uuid' => 'required|exists:types,uuid',
            'source_uuid' => 'required|exists:sources,uuid',
            
            'loan_amount' => 'required|numeric',
            'company_name' => 'required',
            'net_salary' => 'required|numeric',
            'existing_loan_emi'=>'required|numeric',
        ]);
         if ($validator->fails()) {
             return $this->transformItem($validator->messages(), LeadValidationFailedTransformer::class, 400);
         }

        $data = $request->all();
        $lead = $this->leads->newLeadAsConsumers($data);
        return $this->transformItem($lead, LeadTransformer::class, 201);
    }

}
