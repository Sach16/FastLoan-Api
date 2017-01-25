<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Teams\Team;

class TeamTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = [

    ];

    /**
     * @var array
     */
    protected $availableIncludes = [
        'members',
        'referrals',
    ];

    /**
     * @param Team $team
     * @return array
     */
    public function transform(Team $team)
    {
        return [
            'uuid'        => $team->uuid,
            'name'        => $team->name,
            'description' => $team->description,
            'created_at'  => $team->created_at,
            'updated_at'  => $team->updated_at,
        ];
    }

    /**
     * include team owner
     * @param  Team   $team [description]
     * @return User
     */
    public function includeOwner(Team $team)
    {
        return $this->item($team->owner, new UserTransformer);
    }

    /**
     * @param Team $team
     * @return \League\Fractal\Resource\Collection
     */
    public function includeMembers(Team $team)
    {
        $newOwner        = collect([]);
        $newemptyMembers = collect([]);
        $TeamMembers     = collect();
        $TeamMembers     = $team->members;
        $newOwner->push($TeamMembers->reverse()->pop());

        foreach ($TeamMembers as $member) {
            if ($member->attendances->isEmpty()) {
                $newemptyMembers->push($member);
            } else {
                $newOwner->push($member);
            }
        }

        foreach ($newemptyMembers as $member) {
            $newOwner->push($member);
        }
        return $this->collection($newOwner->unique(), new UserTransformer);
    }

    /**
     * @param Team $team
     * @return \League\Fractal\Resource\Collection
     */
    public function includeReferrals(Team $team)
    {
        return $this->collection($team->referrals, new UserTransformer);
    }

}
