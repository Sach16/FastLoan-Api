<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Tasks\Contract;

class UpdateTaskJob extends Job implements ShouldQueue
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
     * @param Contract $tasks
     * @return mixed
     */
    public function handle(Contract $tasks)
    {
        return $tasks->updateAsAdmin($this->request, $this->id);
    }
}
