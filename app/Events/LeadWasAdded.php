<?php

namespace Whatsloan\Events;

use Whatsloan\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Whatsloan\Repositories\Leads\Lead;

class LeadWasAdded extends Event
{

    use SerializesModels;

    /**
     * @var Lead
     */
    private $lead;

    /**
     * Create a new event instance.
     * @param Lead $lead
     */
    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['LEAD_WAS_ADDED'];
    }
}
