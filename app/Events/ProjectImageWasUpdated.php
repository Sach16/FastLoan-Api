<?php

namespace Whatsloan\Events;

use Whatsloan\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Whatsloan\Repositories\Attachments\Attachment;
use Whatsloan\Repositories\Projects\Project;

class ProjectImageWasUpdated extends Event
{
    use SerializesModels;

    /**
     * @var User
     */
    public $project;

    /**
     * @var Attachment
     */
    public $document;

    /**
     * Create a new event instance.
     * @param User $user
     * @param Attachment $document
     */
    public function __construct(Project $project, Attachment $document)
    {
        $this->project = $project;
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
