<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Repositories\Attendances\Attendance;

class RatingTransformer extends TransformerAbstract
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
    ];

    /**
     * @param User $user
     * @return array
     */
    public function transform($user)
    {        
        return [
            'dsa_rating'    => $user->dsa_rating,
            'dsa_wise'      => $user->dsa_wise,
            'city_wise'     => $user->city_wise,
            'all_india'     => $user->all_india,            
        ];
    }  
   
}
