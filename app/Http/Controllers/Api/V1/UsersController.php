<?php

namespace Whatsloan\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Whatsloan\Http\Requests;
use Whatsloan\Http\Controllers\Controller;
use Whatsloan\Http\Transformers\V1\UserTransformer;
use Whatsloan\Http\Transformers\V1\LoanTransformer;
use Whatsloan\Http\Transformers\V1\UserLoanTransformer;
use Whatsloan\Http\Transformers\V1\TrackUserTransformer;
use Whatsloan\Http\Transformers\V1\ResourceUpdated;
use Whatsloan\Http\Requests\Api\V1\UpdateUserTrackingStatusRequest;
use Whatsloan\Repositories\Users\Contract as IUsers;

use Whatsloan\Http\Transformers\V1\TeamTransformer;

class UsersController extends Controller
{

    /**
     * Users
     * @var [type]
     */
    protected $users;

    /**
     * UsersController constructor
     * @param IUsers $users
     */
    public function __construct(IUsers $users)
    {
        $this->users = $users;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //$this->transformCollection([]);
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
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $user = $this->users->show($uuid);
        $user->dsa_rating    = '1.00';//$this->users->dsaRating($uuid);
        $user->dsa_wise      = '12';
        $user->city_wise     = '134';
        $user->all_india     = '150';
        return $this->transformItem($user, UserTransformer::class);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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
     * Get user team
     * @return [type] [description]
     */
    public function team()
    {
       $authUser = \Auth::guard('api')->user();
       $team = $this->users->getTeam($authUser->role,$authUser->uuid)->teams->first();
       return $this->transformItem($team,new TeamTransformer);
    }

    /**
     * Get all user team
     * @return [type] [description]
     */
    public function allTeamUser()
    {
       $authUser = \Auth::guard('api')->user();
       $team = $this->users->getTeam('DSA_OWNER',$authUser->uuid)->teams->first();
       return $this->transformItem($team,new TeamTransformer);
    }
    
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function teamMembers(Request $request) {
        
        $authUser = \Auth::guard('api')->user();
        $members = $this->users->getTeamMembers($authUser->role,$authUser->uuid,$request->all());
        return $this->transformCollection($members,new UserTransformer);
        
    }
    
    public function loans()
    {
        $userLoan = $this->users->getUserLoans();   
        return $this->transformItem($userLoan, new UserLoanTransformer);
    }
    
    public function teamLoans()
    {
        $loan = $this->users->getTeamLoans();   
        return $this->transformCollection($loan, new LoanTransformer);
    }
    
    public function trackStatusLists()
    {
        $trackLists = $this->users->trackLists();   
        return $this->transformCollection($trackLists, new TrackUserTransformer);
    }
    
    public function enableTracking(Request $request, $userId)
    {        
        $user = $this->users->enableTrackingStatus($request, $userId);   
        return $this->transformItem($user, ResourceUpdated::class);
    }
}
