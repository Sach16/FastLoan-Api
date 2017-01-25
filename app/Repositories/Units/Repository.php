<?php

namespace Whatsloan\Repositories\Units;



/**
 * @property User user
 */
class Repository implements Contract
{
    
    
    /**
     * User Model
     * @var
     */
    protected $unit;

    /**
     * Constructor
     * @param User $user - User Model
     */
    public function __construct(Unit $unit)
    {
        $this->unit = $unit;
    }
}

