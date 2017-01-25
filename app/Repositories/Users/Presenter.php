<?php

namespace Whatsloan\Repositories\Users;

class Presenter extends \Laracasts\Presenter\Presenter
{

    /**
     * Name of the user
     * 
     * @return string
     */
    public function name()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}