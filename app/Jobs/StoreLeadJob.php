<?php

namespace Whatsloan\Jobs;

use Illuminate\Http\Request;
use Whatsloan\Events\LeadWasAdded;
use Whatsloan\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Repositories\Leads\Contract as ILeads;

class StoreLeadJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var Request
     */
    private $request;

    /**
     * Create a new job instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @param ILeads $leads
     * @return mixed
     */
    public function handle(ILeads $leads)
    {
        $lead = $leads->add($this->request->all());
        event(LeadWasAdded::class, $lead);

        return $lead;
    }
}
