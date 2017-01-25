<?php

namespace Whatsloan\Http\Transformers\V1;

use League\Fractal\TransformerAbstract;
use Whatsloan\Repositories\Users\User;
use Whatsloan\Repositories\Attendances\Attendance;

class UserLoanTransformer extends TransformerAbstract
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
    public function transform(User $userLoan)
    {
        return [
            'status'       => $userLoan->status,            
        ];
    }  
   
}
