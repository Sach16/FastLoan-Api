<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Users\Contract;

class SetCredentialsJob extends Job
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var array
     */
    public $id;

    /**
     * Create a new job instance.
     *
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @param Contract $users
     * @return mixed
     */
    public function handle(Contract $users)
    {
        return $users->setCredentials($this->id);
    }
}
