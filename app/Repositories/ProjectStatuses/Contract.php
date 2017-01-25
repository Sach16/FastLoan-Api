<?php

namespace Whatsloan\Repositories\ProjectStatuses;

use Illuminate\Http\Request;

interface Contract
{

    
    /**
     * List of all the project statuses
     */
    public function listing();
}
