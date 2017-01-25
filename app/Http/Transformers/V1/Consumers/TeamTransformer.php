<?php

namespace Whatsloan\Http\Transformers\V1\Consumers;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Teams\Team;

class TeamTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = [
          'members',  
    ];

    /**
     * @var array
     */
    protected $availableIncludes = [
        
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
        return $this->item($team->owner, new TeamMemberTransformer);
    }

    /**
     * @param Team $team
     * @return \League\Fractal\Resource\Collection
     */
    public function includeMembers(Team $team)
    {
        return $this->collection($team->members, new TeamMemberTransformer);
    }

}
