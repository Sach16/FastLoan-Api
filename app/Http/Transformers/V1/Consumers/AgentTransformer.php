<?php

namespace Whatsloan\Http\Transformers\V1\Consumers;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Users\User;

class AgentTransformer extends TransformerAbstract
{

    /**
     * @var array
     */
    protected $defaultIncludes = ['banks','teammembers'];

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
//            'role'       => $user->role,
        ];
    }

    /**
     * @param User $user
     * @return \League\Fractal\Resource\Item
     */
    public function includeSettings(User $user)
    {
        $settings = is_array($user->settings) ? $user->settings : [];
        return $this->item($settings, new UserSettingsTransformer);
    }
    
    /**
     * Include address
     * @param  Bank $bank
     * @return
     */
    public function includeAddress(User $user)
    {
        if($user->addresses->first() != null){
            return $this->collection($user->addresses, new AddressTransformer);
        }
    }
    
     public function includeBanks(User $user)
    {
        return $this->collection($user->banks, new BankTransformer);
    }
    
    public function includeTeammembers(User $user)
    {
        return $this->collection($user->teams, new TeamTransformer);
    }
    
}
