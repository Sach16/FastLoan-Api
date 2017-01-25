<?php

namespace Whatsloan\Listeners;

use Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Whatsloan\Events\CustomerWasRegistered;

class SendWelcomeEmailToCustomer
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
     * @param  CustomerWasRegistered  $event
     * @return void
     */
    public function handle(CustomerWasRegistered $event)
    {
        $user = $event->user;
        Mail::send('emails.new-customer', ['user' => $user], function ($m) use ($user){
            $m->from('donotreply@whatsloan.com', 'Whatsloan');

            $m->to($user->email, $user->present()->name)->subject('Welcome to Whatsloan');
        });
    }
}
