<?php

namespace Whatsloan\Events;

use Whatsloan\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Whatsloan\Repositories\Attachments\Attachment;
use Whatsloan\Repositories\Users\User;

class CustomerDocumentWasUpdated extends Event
{
    use SerializesModels;

    /**
     * @var User
     */
    public $user;

    /**
     * @var Attachment
     */
    public $document;

    /**
     * Create a new event instance.
     * @param User $user
     * @param Attachment $document
     */
    public function __construct(User $user, Attachment $document)
    {
        $this->user = $user;
        $this->document = $document;
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
