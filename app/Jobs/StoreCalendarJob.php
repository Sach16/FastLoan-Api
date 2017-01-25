<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Calendars\Contract;

class StoreCalendarJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var array
     */
    public $request;

    /**
     * @var
     */
    public $teamId;

    /**
     * Create a new job instance.
     *
     * @param array $request
     * @param $teamId
     */
    public function __construct(array $request, $teamId)
    {
        $this->request = $request;
        $this->teamId = $teamId;
    }

    /**
     * Execute the job.
     *
     * @param Contract $calendars
     * @return mixed
     */
    public function handle(Contract $calendars)
    {
        return $calendars->store($this->request, $this->teamId);
    }
}
