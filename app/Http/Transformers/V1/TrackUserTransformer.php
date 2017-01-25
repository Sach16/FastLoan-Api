<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Users\TrackUser;

class TrackUserTransformer extends TransformerAbstract
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
     * @param User Tracking Status
     * @return array
     */
    public function transform($track_user)
    {        
        return [
            'status'     => $track_user,            
        ];
    }
   
}
