<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\TaskStatuses\Contract;

class UpdateTaskStatusJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var array
     */
    public $request;

    /**
     * @var
     */
    public $id;

    /**
     * Create a new job instance.
     *
     * @param array $request
     * @param $id
     */
    public function __construct(array $request, $id)
    {
        $this->request = $request;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @param Contract $statuses
     * @return mixed
     */
    public function handle(Contract $statuses)
    {
        return $statuses->updateAsAdmin($this->request, $this->id);
    }
}
