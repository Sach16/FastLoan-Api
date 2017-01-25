<?php

namespace Whatsloan\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Jobs\StoreLeadJob;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Transformers\V1\LeadTransformer;
use Whatsloan\Http\Transformers\V1\SourceTransformer;
use Whatsloan\Http\Transformers\V1\LeadCountTransformer;
use Whatsloan\Repositories\Leads\Contract as Leads;
use Whatsloan\Repositories\Users\Contract as Users;
use Whatsloan\Repositories\Sources\Source;
use Whatsloan\Http\Requests\Api\V1\StoreLeadRequest;
use Whatsloan\Http\Requests\Api\V1\UpdateLeadRequest;
use Whatsloan\Http\Transformers\V1\ResourceCreated;


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
        $ids = ($authUser->role == 'DSA_OWNER')
                    ? $this->users->getMemberIds($authUser->uuid)
                    : [$authUser->id];
     
        $result = [
            'loanTypeCount' => [
                'data' => [
                    'leads' => [
                        'data' => [
                            'HL' => $this->leads->getLeadsCount('HL',$ids),
                            'PL' => $this->leads->getLeadsCount('PL',$ids),
                        ]
                    ]
                ]
            ],
            'leads' => $this->transformCollection($this->leads->getLeadsByUserIds($ids), LeadTransformer::class),            
        ];
        
        return $this->transformMultipleWithPaginaotr($result, LeadCountTransformer::class);       
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
    
    /**
     * Display a listing of the source.
     *
     * @return \Illuminate\Http\Response
     */
    public function source()
    {
        $source = Source::get();
        return $this->transformCollection($source, SourceTransformer::class);        
    }
    
}
