<?php

namespace App\Providers;

use App\Events\AddRoleEvent;
use App\Events\AddSubscriptionEvent;
use App\Events\AddTransactionEvent;

use App\Listeners\AddRoleListener;
use App\Listeners\AddSubscriptionListener;
use App\Listeners\AddTransactionListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        AddRoleEvent::class => [

            AddRoleListener::class,
        ],
        AddSubscriptionEvent::class => [

            AddSubscriptionListener::class,
        ],
        AddTransactionEvent::class => [

            AddTransactionListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
