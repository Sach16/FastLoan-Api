<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Queries\Contract;

class UpdateQueryJob extends Job
{

    use InteractsWithQueue,
        SerializesModels;

    /**
     * Request parameters
     *
     * @var array
     */
    protected $request;
    
    
    
    /**
     * Request parameters
     *
     * @var array
     */
    protected $uuid;
    
    

    /**
     * Create a new job instance.
     * @param array $request
     */
    public function __construct($uuid,$request)
    {
        $this->request = $request;
        $this->uuid = $uuid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Contract $queries)
    {
        return $queries->update($this->uuid,$this->request);
    }

}
