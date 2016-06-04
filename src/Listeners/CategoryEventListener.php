<?php

namespace ZEDx\Listeners;

use ZEDx\Jobs\UpdateCache;

class CategoryEventListener
{
    /**
     * Handle category create events.
     */
    public function onCategoryCreate($event)
    {
    }

    /**
     * Handle category update events.
     */
    public function onCategoryUpdate($event)
    {
        dispatch(new UpdateCache($event->category));
    }

    /**
     * Handle category delete events.
     */
    public function onCategoryDelete($event)
    {
        dispatch(new UpdateCache($event->category, true));
    }

    /**
     * Handle category move events.
     */
    public function onCategoryMove($event)
    {
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
        $class = "ZEDx\Listeners\CategoryEventListener";

        $events->listen('ZEDx\Events\Category\CategoryWasCreated', $class.'@onCategoryCreate');
        $events->listen('ZEDx\Events\Category\CategoryWasUpdated', $class.'@onCategoryUpdate');
        $events->listen('ZEDx\Events\Category\CategoryWasDeleted', $class.'@onCategoryDelete');
        $events->listen('ZEDx\Events\Category\CategoryWasMoved', $class.'@onCategoryMove');
    }
}
