<?php

namespace ZEDx\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'ZEDx\Listeners\AdEventListener',
        'ZEDx\Listeners\AdtypeEventListener',
        'ZEDx\Listeners\CategoryEventListener',
        'ZEDx\Listeners\FieldEventListener',
        'ZEDx\Listeners\MenuEventListener',
        'ZEDx\Listeners\PageEventListener',
        'ZEDx\Listeners\PaymentEventListener',
        'ZEDx\Listeners\SubscriptionEventListener',
        'ZEDx\Listeners\TemplateEventListener',
        'ZEDx\Listeners\UserEventListener',
    ];

    /**
     * Register any other events for your application.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     *
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
