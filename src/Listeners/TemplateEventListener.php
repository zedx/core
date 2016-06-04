<?php

namespace ZEDx\Listeners;

use ZEDx\Jobs\UpdateCache;

class TemplateEventListener
{
    /**
     * Handle template create events.
     */
    public function onTemplateCreate($event)
    {
        dispatch(new UpdateCache($event->template));
    }

    /**
     * Handle template delete events.
     */
    public function onTemplateDelete($event)
    {
        dispatch(new UpdateCache($event->template, true));
    }

    /**
     * Handle template update events.
     */
    public function onTemplateUpdate($event)
    {
        dispatch(new UpdateCache($event->template));
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Illuminate\Events\Dispatcher $events
     *
     * @return array
     */
    public function subscribe($events)
    {
        $class = "ZEDx\Listeners\TemplateEventListener";

        $events->listen('ZEDx\Events\Template\TemplateWasCreated', $class.'@onTemplateCreate');
        $events->listen('ZEDx\Events\Template\TemplateWasDeleted', $class.'@onTemplateDelete');
        $events->listen('ZEDx\Events\Template\TemplateWasUpdated', $class.'@onTemplateUpdate');
    }
}
