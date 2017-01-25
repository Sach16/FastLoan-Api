<?php

namespace Whatsloan\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Whatsloan\Jobs\Job;
use Whatsloan\Repositories\Feedbacks\Contract;

class UpdateFeedbackQuestionJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var array
     */
    public $request;

    /**
     * @var
     */
    public $id;

    /**
     * Create a new job instance.
     *
     * @param array $request
     * @param $id
     */
    public function __construct(array $request, $id)
    {
        $this->request = $request;
        $this->id      = $id;
    }

    /**
     * Execute the job.
     *
     * @param Contract Feedback Categories
     * @return mixed
     */
    public function handle(Contract $feedback)
    {
        return $feedback->updateAsAdmin($this->id, $this->request);
    }
}
