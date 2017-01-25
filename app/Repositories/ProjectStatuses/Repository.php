<?php

namespace Whatsloan\Repositories\ProjectStatuses;

use Illuminate\Http\Request;

class Repository implements Contract
{

    private $projectStatus;

    /**
     * ProjectStatus repository constructor
     * @param \Whatsloan\Repositories\Loans\Loan $projectStatus
     */
    public function __construct(ProjectStatus $projectStatus)
    {
        $this->projectStatus = $projectStatus;
    }

    /**
     * List all project statuses
     * @return type
     */
    public function listing()
    {
        return $this->projectStatus->get();
    }

}
