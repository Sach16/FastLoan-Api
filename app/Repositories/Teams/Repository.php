<?php
namespace Whatsloan\Repositories\Teams;

use Carbon\Carbon;
use Whatsloan\Repositories\Leads\Lead;
use Whatsloan\Repositories\Users\User;

class Repository implements Contract
{

    /**
     * Team Model
     * @var
     */
    protected $team;

    /**
     * Constructor
     * @param Team $team - Team Model
     */
    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    /**
     * Get a paginated list of leads
     *
     * @param int $limit
     * @return mixed
     */
    public function paginate($limit = 15)
    {
        return $this->team->with(['members'])->paginate($limit);
    }

    /**
     * Filter team member by member list
     * @param  string  $teamUuid
     * @param  string  $memberUuidList
     * @param  integer $limit
     * @return collection
     */
    public function getTeamDetailsOfMember($teamUuid, $memberUuid, $limit = 15)
    {
        return $this->team->with(['owner', 'members' => function ($query) use ($memberUuid) {
            $query->whereIn('uuid', [$memberUuid]);
        }])->whereUuid($teamUuid)->paginate($limit);
    }

    /**
     * Get the team details of owner
     * @param  string $ownerUuid
     * @return
     */
    public function getTeamDetailsByOwner($owneId, $limit = 15)
    {
        return $this->team->with(['owner', 'members'])->whereUserId($owneId)->paginate($limit);
    }

    /**
     * Get the details of a single team
     *
     * @param $uuid
     * @return mixed
     */
    public function find($uuid)
    {
        return $this->team->with(['members'])->whereUuid($uuid)->firstOrFail();
    }

    /**
     * Get Dsa Owner Team
     * @param  string  $teamUuid
     * @param  integer $limit
     * @return Team
     */
    public function getDsaOwnerTeam($teamUuid, $limit = 15)
    {
        return $this->team
            ->with([
                'members'             => function ($q) {
                    $q->where('is_owner', false);
                },
                'members.attendances' => function ($query) {
                    $query->whereDate('start_time', '=', date('Y-m-d'));
                },
            ])
            ->whereUuid($teamUuid)
            ->paginate($limit);
    }

    /**
     * Get Dsa Owner Team List
     * @param  string  $teamUuid
     * @param  integer $limit
     * @return Team
     */
    public function getDsaOwnerTeamList($teamUuid, $limit = 15)
    {
        return $this->team
            ->with(['members' => function ($query) {
                $query->leftJoin('attendances', 'attendances.user_id', '=', 'users.id')
                    ->orderBy('team_user.is_owner', 'DESC')
                    ->orderBy('attendances.start_time', 'desc')
                    ->groupBy('id');
            }, 'members.attendances' => function ($q) {
                $q->whereBetween('attendances.start_time', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()]);
                $q->orderBy('attendances.start_time', 'desc');
            }])
            ->whereUuid($teamUuid)
            ->paginate($limit);
    }

    /**
     * GetDsaMemberTeam
     * @param  string $teamUuid
     * @param  integer  $memberId
     * @param  integer $limit
     * @return Team
     */
    public function getDsaMemberTeam($teamUuid, $memberId, $limit = 15)
    {
        return $this->team
            ->with([
                'members'             => function ($q) use ($memberId) {
                    $q->where('id', $memberId);
                },
                'members.attendances' => function ($query) {
                    $query->whereDate('start_time', '=', date('Y-m-d'));
                },
            ])
            ->whereUuid($teamUuid)
            ->paginate($limit);

    }

    /**
     *
     * @param type $teamId
     * return type object
     */
    public function getTeamReferrals($teamId)
    {
        return $team = $this->team
            ->with(['referrals' => function ($q) use ($teamId) {
                $q->where('team_id', $teamId);
            }])
            ->whereId($teamId)
            ->paginate();
    }

    /**
     * Update a team as admin
     *
     * @param array $request
     * @param $id
     * @return mixed
     */
    public function updateAsAdmin(array $request, $id)
    {
        $team = $this->team->find($id);
        $team->update($request);
        $owner_id='';
        
        foreach ($request['owner_id'] as $owner_id) {
            $sync[$owner_id] = ['is_owner' => true];
        }
        
        $owner = User::with('banks')->where('id', $owner_id)->first();
        
        if($owner->banks->isEmpty()){
            $bank_id = $request['bank'];
            User::where('id', $request['owner_id'])->update(['role' => 'DSA_OWNER']);
            $owner = User::where('id', $request['owner_id'])->first();
            $owner->banks()->sync([$request['bank']]);
        }else{
            $bank_id = $owner->banks()->first()->pivot->bank_id;
        }

        if( isset($request['members']) && !empty($request['members']))
        { 
            foreach ($request['members'] as $member) {
                $sync[$member] = ['is_owner' => false];
                User::where('id', $member)->update(['role' => 'DSA_MEMBER']);
                $user = User::where('id', $member)->first();
                $user->banks()->sync([$bank_id]);
            }
        }

        $team->members()->sync($sync);
        if( isset($request['members']) && !empty($request['members']))
        {
            $this->UpdateUserRole($request['members']);
        }
        return $team;
    }

    /**
     * Store a new team
     *
     * @param array $request
     * @return mixed
     */
    public function storeAsAdmin(array $request)
    {
        $request['uuid'] = uuid();
        $team            = $this->team->create($request);

        $sync[$request['owner_id']] = ['is_owner' => true];
        User::where('id', $request['owner_id'])->update(['role' => 'DSA_OWNER']);
        $owner = User::where('id', $request['owner_id'])->first();
        $owner->banks()->sync([$request['bank']]);
        if (!empty($request['members'])) {
            foreach ($request['members'] as $member) {
                $sync[$member] = ['is_owner' => false];
                User::where('id', $member)->update(['role' => 'DSA_MEMBER']);
                $user = User::where('id', $member)->first();
                $user->banks()->sync([$request['bank']]);
            }
        }

        $team->members()->sync($sync);

        return $team;
    }

    /**
     * Store a multiple owner to team
     *
     * @param array $request
     * @return mixed
     */
    public function addMultiOwner($owner_id, $team_id)
    {
        $team = $this->team->find($team_id);

        $sync[$owner_id] = ['is_owner' => true];
        User::where('id', $owner_id)->update(['role' => 'DSA_OWNER']);
        $owner = User::where('id', $owner_id)->first();
        $team->members()->updateExistingPivot($owner_id,['is_owner' => true]);

        return $team;
    }

    /**
     * Update DSA NON MEMBER Status to DSA MEMEBER
     * @param array $members Users IDs
     * @return True
     */
    private function UpdateUserRole(array $members)
    {
        User::whereIn('id', $members)->update(['role' => 'DSA_MEMBER']);
    }
    /**
     * Update Team Member Settings
     * @param $team_id
     * @param $member_id
     * @return True
     */
    public function updateSettings(array $request, $team_id, $member_id)
    {
        $team = $this->team->find($team_id);
        $team->members()->updateExistingPivot($member_id, array_except($request, ['reports_to']));
        if (!empty($request['reports_to'])) {
            User::find($member_id)->reportsTo()->sync($request['reports_to']);
        } else {
            $team_owner = $team->load(['members' => function ($query) {
                $query->where('is_owner', '1');
            }]);
            // default select team owner as report to
            if ($team_owner->members[0]->id != $member_id) {
                User::find($member_id)->reportsTo()->sync([$team_owner->members[0]->id]);
            }

        }
    }

    /**
     * Update a Remove DSA from Team and assign his cutomer to Other Member
     *
     * @param array $request
     * @param $team_id
     * @param $member_id
     * @return mixed
     */
    public function updateRemoveDsaFromTeam(array $request, $team_id, $member_id)
    {
        $leads = Lead::whereHas('user', function ($q) {
            $q->where('role', 'CONSUMER');
        })
            ->where('assigned_to', $member_id)
            ->get();

        foreach ($leads as $lead) {
            $lead->update(['assigned_to' => $request['assigned_to']]);
        }

        User::where('id', $member_id)->update(['role' => 'DSA_NONMEMBER']);
        $team = $this->team->find($team_id);
        $team->members()->detach($member_id);
        User::find($member_id)->reportsTo()->detach();
        $user = User::where('id', $member_id)->first();
        $user->banks()->detach();
    }
}
