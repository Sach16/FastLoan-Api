<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Users\Contract;

class StoreCustomerDocumentJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var array
     */
    public $request;

    /**
     * @var
     */
    public $customerId;

    /**
     * Create a new job instance.
     * @param array $request
     * @param $customerId
     */
    public function __construct(array $request, $customerId)
    {
        $this->request = $request;
        $this->customerId = $customerId;
    }

    /**
     * Execute the job.
     *
     * @param Contract $users
     * @return mixed
     */
    public function handle(Contract $users)
    {
        return $users->storeDocumentAsAdmin($this->request, $this->customerId);
    }
}
