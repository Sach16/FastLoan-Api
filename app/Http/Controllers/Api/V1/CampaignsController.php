<?php

namespace Whatsloan\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Transformers\V1\CampaignTransformer;
use Whatsloan\Repositories\Campaigns\Contract as ICampaigns;
use Whatsloan\Repositories\Users\Contract as Users;

class CampaignsController extends Controller
{
    /**
     * @var ICampaigns
     */
    private $campaigns;
    
    /**
    * @var Leads
    */
    private $users;

    /**
     * CampaignsController constructor.
     * @param ICampaigns $campaigns, Users $users
     */
    public function __construct(ICampaigns $campaigns,Users $users)
    {
        $this->campaigns = $campaigns;
        $this->users = $users;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $authUser = \Auth::guard('api')->user();
        $team_id = $this->users->getTeamId($authUser->uuid);
        $campaigns = $this->campaigns->paginate($request->all(),$team_id->id);
        return $this->transformCollection($campaigns, CampaignTransformer::class);
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
