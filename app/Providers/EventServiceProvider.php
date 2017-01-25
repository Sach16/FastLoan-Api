<?php

namespace Whatsloan\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Whatsloan\Events\LeadWasAdded;
use Whatsloan\Events\CustomerWasRegistered;
use Whatsloan\Events\CustomerDocumentWasUpdated;
use Whatsloan\Listeners\SendWelcomeEmailToCustomer;
use Whatsloan\Listeners\SendDocumentEmailToCustomer;
use Whatsloan\Listeners\SendDocumentSmsToCustomer;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        LeadWasAdded::class => [],
        CustomerWasRegistered::class => [
            SendWelcomeEmailToCustomer::class
        ],
        CustomerDocumentWasUpdated::class => [
            //SendDocumentEmailToCustomer::class,
            //SendDocumentSmsToCustomer::class,
        ]
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
