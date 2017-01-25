<?php

namespace Whatsloan\Http\Transformers\V1;
use Whatsloan\Repositories\Users\User;
use League\Fractal\TransformerAbstract;

class UserReportsToTransformer extends TransformerAbstract
{

    /**
     * User settings transformer
     *
     * @param array $settings
     * @return array
     */
    public function transform(User $user)
    {        
        return [            
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,            
            'phone'      => $user->phone,            
        ];
    }
}