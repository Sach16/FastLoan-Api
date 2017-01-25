<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Builders\Contract;

class UpdateBuilderJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var
     */
    public $request;

    /**
     * @var
     */
    public $id;

    /**
     * Create a new job instance.
     * @param $request
     * @param $id
     */
    public function __construct($request, $id)
    {
        $this->id = $id;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @param Contract $builders
     * @return mixed
     */
    public function handle(Contract $builders)
    {
        return $builders->update($this->request, $this->id);
    }
}
