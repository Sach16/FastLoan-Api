<?php

namespace Whatsloan\Http\Controllers\Api\V1\Consumers;

use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Repositories\Leads\Contract as Leads;
use Whatsloan\Repositories\Users\Contract as Users;
use Whatsloan\Http\Transformers\V1\Consumers\LeadTransformer;
use Whatsloan\Http\Transformers\V1\Consumers\ReferralTransformer;
use Whatsloan\Http\Transformers\V1\Consumers\RefferalValidationFailedTransformer ;


use Illuminate\Support\Facades\Validator;

class ReferralsController extends Controller
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
        $referrals = $this->leads->getReferralsAsConsumers($authUser->id);
        return $this->transformCollection($referrals, ReferralTransformer::class);
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
    
    public function newreferral(Request $request){
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|min:8|unique:users',
            'email' => 'required|unique:users',
            'city_uuid' => 'required|exists:cities,uuid',
            'type_uuid' => 'required|exists:types,uuid',           
            'loan_amount' => 'required|numeric',
            'company_name' => 'required',
            'net_salary' => 'required|numeric',
        ]);
         if ($validator->fails()) {
             return $this->transformItem($validator->messages(), RefferalValidationFailedTransformer::class, 400);
         }
        
         $data = $request->all();
         $referrals = $this->leads->referralAsConsumers($data);
         return $this->transformItem($referrals, LeadTransformer::class, 201);
         
    }
}
