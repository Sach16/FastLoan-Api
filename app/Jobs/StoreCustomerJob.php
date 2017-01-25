<?php

namespace Whatsloan\Jobs;

use Whatsloan\Events\CustomerWasRegistered;
use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Users\Contract;

class StoreCustomerJob extends Job
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var array
     */
    public $request;

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
     * @param Contract $users
     * @return mixed
     */
    public function handle(Contract $users)
    {
        $user = $users->storeAsAdmin($this->request);
        //event(new CustomerWasRegistered($user));
        return $user;
    }
}
