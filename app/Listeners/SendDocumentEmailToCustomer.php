<?php

namespace Whatsloan\Listeners;

use Mail;
use Whatsloan\Events\CustomerDocumentWasUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendDocumentEmailToCustomer
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CustomerDocumentWasUpdated  $event
     * @return void
     */
    public function handle(CustomerDocumentWasUpdated $event)
    {
        $user = $event->user;
        $document = $event->document;
        Mail::queue('emails.new-customer-document', ['user' => $user, 'document' => $document], function ($m) use ($user) {
            $m->from('donotreply@whatsloan.com', 'Whatsloan');

            $m->to($user->email, $user->present()->name)->subject('Whatsloan - Documents uploaded');
        });
    }
}
