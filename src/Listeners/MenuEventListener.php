<?php

namespace ZEDx\Listeners;

use ZEDx\Jobs\UpdateCache;

class MenuEventListener
{
    /**
     * Handle menu create events.
     */
    public function onMenuCreate($event)
    {
        dispatch(new UpdateCache($event->menu));
    }

    /**
     * Handle menu delete events.
     */
    public function onMenuDelete($event)
    {
        dispatch(new UpdateCache($event->menu, true));
    }

    /**
     * Handle menu update events.
     */
    public function onMenuUpdate($event)
    {
        dispatch(new UpdateCache($event->menu));
    }

    /**
     * Handle menu move events.
     */
    public function onMenuMove($event)
    {
        dispatch(new UpdateCache($event->menu));
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
        $class = "ZEDx\Listeners\MenuEventListener";

        $events->listen('ZEDx\Events\Menu\MenuWasCreated', $class.'@onMenuCreate');
        $events->listen('ZEDx\Events\Menu\MenuWasDeleted', $class.'@onMenuDelete');
        $events->listen('ZEDx\Events\Menu\MenuWasUpdated', $class.'@onMenuUpdate');
        $events->listen('ZEDx\Events\Menu\MenuWasMoved', $class.'@onMenuMove');
    }
}
