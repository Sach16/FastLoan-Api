<?php

namespace Whatsloan\Repositories\Roles;

class Repository implements Contract
{


    /**
     * User Model
     * @var
     */
    protected $role;


    /**
     * Constructor
     * @param User $user - User Model
     */
    public function __construct(User $role)
    {
        $this->role = $role;

    }
}
