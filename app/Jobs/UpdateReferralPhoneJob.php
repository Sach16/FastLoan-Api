<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Users\Contract;

class UpdateReferralPhoneJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    
    /**
     * @var array
     */
    public $request;

    /**
     * Create a new job instance.
     *
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
        return $users->transferPhoneAsAdmin($this->request);
    }
}
