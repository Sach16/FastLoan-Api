<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Whatsloan\Repositories\Queries\Contract;

class StoreQueryJob extends Job
{
    use InteractsWithQueue, SerializesModels;
    
    
    
     /**
     * Request parameters
     *
     * @var array
     */
    protected $request;
    
    

     /**
     * Create a new job instance.
     * @param array $request
     */
    public function __construct($request)
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
        return $queries->store($this->request);
    }
}
