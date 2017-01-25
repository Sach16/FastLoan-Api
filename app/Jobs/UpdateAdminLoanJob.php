<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Loans\Contract;
use Whatsloan\Repositories\Loans\Loan;

class UpdateAdminLoanJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var array
     */
    public $request;
    /**
     * @var
     */
    private $id;

    /**
     * Create a new job instance.
     * @param $id
     * @param array $request
     */
    public function __construct($id, array $request)
    {
        $this->request = $request;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @param Contract $banks
     * @return mixed
     */
    public function handle(Contract $loans)
    {
        return $loans->updateAsAdmin($this->id, $this->request);
    }
}
