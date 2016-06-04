<?php

namespace ZEDx\Listeners;

use ZEDx\Jobs\UpdateCache;

class FieldEventListener
{
    /**
     * Handle field create events.
     */
    public function onFieldCreate($event)
    {
        dispatch(new UpdateCache($event->field));
    }

    /**
     * Handle field delete events.
     */
    public function onFieldDelete($event)
    {
        dispatch(new UpdateCache($event->field, true));
    }

    /**
     * Handle field update events.
     */
    public function onFieldUpdate($event)
    {
        dispatch(new UpdateCache($event->field));
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
        $class = "ZEDx\Listeners\FieldEventListener";

        $events->listen('ZEDx\Events\Field\FieldWasCreated', $class.'@onFieldCreate');
        $events->listen('ZEDx\Events\Field\FieldWasDeleted', $class.'@onFieldDelete');
        $events->listen('ZEDx\Events\Field\FieldWasUpdated', $class.'@onFieldUpdate');
    }
}
