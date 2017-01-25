<?php

namespace Whatsloan\Repositories\UserFeedback;

class Repository implements Contract
{

    /**
     * @var City
     */
    private $userFeedback;

    /**
     * City repository constructor
     * @param City $city
     */
    public function __construct(UserFeedback $userFeedback)
    {
        $this->userFeedback = $userFeedback;
    }
    public function paginate($limit = 15){
        
    }

}
