<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Queries\Contract;

class StoreProjectQueryAsAdminJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    /**
     * @var array
     */
    private $request;

    /**
     * Create a new job instance.
     * @param array $request
     */
    public function __construct(array $request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @param Contract $queries
     * @return mixed
     */
    public function handle(Contract $queries)
    {
        return $queries->storeAsAdmin($this->request);
    }
}
