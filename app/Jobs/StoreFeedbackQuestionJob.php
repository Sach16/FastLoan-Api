<?php

namespace Whatsloan\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Whatsloan\Jobs\Job;
use Whatsloan\Repositories\Feedbacks\Contract;

class StoreFeedbackQuestionJob extends Job implements ShouldQueue
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
     * @param Contract  feedback
     * @return mixed
     */
    public function handle(Contract $feedback)
    {
        return $feedback->storeAsAdmin($this->request);
    }
}
