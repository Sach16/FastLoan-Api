<?php

namespace Whatsloan\Jobs;

use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Campaigns\Contract;

class UpdateCampaignJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var array
     */
    private $request;
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
        $this->id = $id;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @param Contract $campaigns
     * @return mixed
     */
    public function handle(Contract $campaigns)
    {
        return $campaigns->update($this->id, $this->request);
    }
}
