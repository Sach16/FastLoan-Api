<?php

namespace Whatsloan\Repositories\Homes;

use Illuminate\Http\Request;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Repositories\Leads\Contract as Leads;
use Whatsloan\Repositories\Teams\Contract as Teams;
use Whatsloan\Repositories\Users\Contract as Users;
use Whatsloan\Repositories\Loans\Contract as Loans;

class Repository implements Contract
{

    /**
     * @var Lead
     */
    private $lead;

    /**
     * @var Team
     */
    private $team;


    /**
     * @var [type]
     */
    private $user;

    /**
     * @var [type]
     */
    private $loan;

    /**
     * Repository constructor.
     * @param Lead $lead
     */
    public function __construct(Leads $lead, Teams $team, Users $user, Loans $loan)
    {
        $this->lead = $lead;
        $this->team = $team;
        $this->user = $user;
        $this->loan = $loan;
    }

    /**
     * Get lead count
     * @return int
     */
    public function getLeadCount()
    {
        $ids = $this->user->getTeamMemberIds();
        return $this->loan->getLoanUserCount($ids,'LEAD');
    }

    /**
     * Get Customer count
     * @return int
     */
    public function getCustomerCount()
    {
        $ids = $this->user->getTeamMemberIds();
        return $this->loan->getLoanUserCount($ids,'CONSUMER');
    }

    /**
     * Get Team
     * @return Collection
     */
    public function getTeam()
    {
        $authUser = \Auth::guard('api')->user();
        $user = $this->user->find($authUser->uuid);

        if ($user->teams->isEmpty()) return collect();

        return ($authUser->role == 'DSA_MEMBER')
            ? $this->team->getDsaMemberTeam($user->teams->first()->uuid, $user->id)
            : $this->team->getDsaOwnerTeamList($user->teams->first()->uuid);

    }
    
    public function trackingStatus()
    {
        $authUser = \Auth::guard('api')->user();
        return $tracking_status = User::whereUuid($authUser->uuid)->first()->track_status;        
    }
}
