<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Loans\Contract;

class UpdateTaskLoanDocumentJob extends Job
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var array
     */
    public $request;
    /**
     * @var loan object
     */
    public $loan;

    /**
     * Create a new job instance.
     *
     * @param array $request
     */
    public function __construct(array $request,$loan)
    {
        $this->request = $request;
        $this->loan  = $loan;
    }

    /**
     * Execute the job.
     *
     * @param Contract $projects
     * @return mixed
     */
    public function handle(Contract $loans)
    {
      $loans  = $this->loan;
        if (isset($this->request['attachment'])) {
            $attachment = $loans->attachments()->update([
                'uri'           => $this->request['attachment'],
            ]);
        }
        $attachment = $loans->attachments()->update([
            'name'          => $this->request['name'],
        ]);
    }
}
