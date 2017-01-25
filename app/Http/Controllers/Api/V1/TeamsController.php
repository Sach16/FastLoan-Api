<?php

namespace Whatsloan\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Transformers\V1\TeamTransformer;
use Whatsloan\Repositories\Teams\Contract as Teams;
use Whatsloan\Repositories\Users\Contract as Users;

class TeamsController extends Controller
{

    /**
     * @var Leads
     */
    private $teams;

    /**
    * @var Leads
    */
    private $users;
    /**
     * LeadsController constructor
     *
     * @param Leads $leads
     */
    public function __construct(Teams $teams,Users $users)
    {
        $this->teams = $teams;
        $this->users = $users;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = $this->teams->paginate();
        return $this->transformCollection($teams, TeamTransformer::class);
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
    public function show($uuid)
    {
        $team = $this->teams->find($uuid);
        return $this->transformItem($team, TeamTransformer::class);
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
    
    /**
     * Get Team Referrals
     */
    public function referrals() 
    {
        $authUser = \Auth::guard('api')->user();
        $team = $this->users->getTeamId($authUser->uuid);        
        $team_referrals = $this->teams->getTeamReferrals($team->id);
        return $this->transformCollection($team_referrals, TeamTransformer::class);
        
    }   

}
