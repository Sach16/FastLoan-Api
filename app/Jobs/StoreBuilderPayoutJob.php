<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Projects\Contract;

class StoreBuilderPayoutJob extends Job implements ShouldQueue
{

    use InteractsWithQueue,
        SerializesModels;

    /**
     * @var array
     */
    public $request;

    /**
     * Create a new job instance.
     * @param array $request
     * @param $id
     */
    public function __construct(array $request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @param Contract $states
     * @return mixed
     */
    public function handle(Contract $project)
    {
        return $project->StorePayoutAsAdmin($this->request);
    }

}
