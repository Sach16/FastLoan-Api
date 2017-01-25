<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Banks\Contract;

class UpdateProjectApprovalStatusJob extends Job //implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var
     */
    public $request;

    /**
     * @var
     */
    public $bankId;

    /**
     * @var
     */
    public $projectId;

    /**
     * Create a new job instance.
     * @param $request
     * @param $bankId
     * @param $projectId
     */
    public function __construct($request, $bankId, $projectId)
    {
        $this->request = $request;
        $this->bankId = $bankId;
        $this->projectId = $projectId;
    }

    /**
     * Execute the job.
     *
     * @param Contract $banks
     * @return mixed
     */
    public function handle(Contract $banks)
    {
        return $banks->updateProjectApproval(
            $this->request,
            $this->bankId,
            $this->projectId
        );
    }
}
