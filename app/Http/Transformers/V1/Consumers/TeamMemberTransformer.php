<?php

namespace Whatsloan\Http\Transformers\V1\Consumers;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Users\User;

class TeamMemberTransformer extends TransformerAbstract
{

    /**
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'uuid'       => $user->uuid,
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'email'      => $user->email,
            'phone'      => $user->phone,
            'role'       => $user->role,
        ];
    }

    
    
}
