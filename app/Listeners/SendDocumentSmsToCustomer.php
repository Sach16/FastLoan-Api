<?php

namespace Whatsloan\Listeners;

use Whatsloan\Events\CustomerDocumentWasUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Services\Sms\Contract;

class SendDocumentSmsToCustomer
{
    /**
     * @var Contract
     */
    private $sms;

    /**
     * Create the event listener.
     * @param Contract $sms
     */
    public function __construct(Contract $sms)
    {
        $this->sms = $sms;
    }

    /**
     * Handle the event.
     *
     * @param  CustomerDocumentWasUpdated  $event
     * @return void
     */
    public function handle(CustomerDocumentWasUpdated $event)
    {
        return $this->sms->sendMessage($event->user->phone, 'New document updated to your account - ' . $event->document->name);
    }
}
