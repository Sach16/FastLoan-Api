<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Queries\Contract;

class UpdateProjectQueryJob extends Job implements ShouldQueue
{

    use InteractsWithQueue, SerializesModels;

    /**
     * @var
     */
    private $request;
    
    /**
     * @var
     */
    private $queryId;

    /**
     * Create a new job instance.
     * @param $request
     * @param $queryId
     */
    public function __construct($request, $queryId)
    {
        $this->request = $request;
        $this->queryId = $queryId;
    }

    /**
     * Execute the job.
     *
     * @param Contract $queries
     * @return mixed
     */
    public function handle(Contract $queries)
    {
        return $queries->updateAsAdmin($this->request, $this->queryId);
    }
}
