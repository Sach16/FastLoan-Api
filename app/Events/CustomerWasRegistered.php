<?php

namespace Whatsloan\Events;

use Whatsloan\Events\Event;
use Illuminate\Queue\SerializesModels;
use Whatsloan\Repositories\Users\User;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CustomerWasRegistered extends Event
{
    use SerializesModels;

    /**
     * @var User
     */
    public $user;

    /**
     * Create a new event instance.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
