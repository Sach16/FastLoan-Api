<?php

namespace Whatsloan\Repositories\Homes;

use Illuminate\Http\Request;

interface Contract
{

    /**
     * Get lead count
     * @return mixed
     */
    public function getLeadCount();


    /**
     * Get Team details
     * @return mixed
     */
    public function getTeam();
}
