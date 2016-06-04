<?php

namespace ZEDx\Listeners;

use ZEDx\Jobs\UpdateCache;

class PageEventListener
{
    /**
     * Handle page create events.
     */
    public function onPageCreate($event)
    {
        dispatch(new UpdateCache($event->page));
    }

    /**
     * Handle page delete events.
     */
    public function onPageDelete($event)
    {
        dispatch(new UpdateCache($event->page, true));
    }

    /**
     * Handle page update events.
     */
    public function onPageUpdate($event)
    {
        dispatch(new UpdateCache($event->page));
    }

    /**
     * Handle page template switch events.
     */
    public function onPageTemplateSwitch($event)
    {
        dispatch(new UpdateCache($event->page));
    }

    /**
     * Handle page themepartial attach events.
     */
    public function onPageThemepartialAttach($event)
    {
        dispatch(new UpdateCache($event->page));
    }

    /**
     * Handle page themepartial detach events.
     */
    public function onPageThemepartialDetach($event)
    {
        dispatch(new UpdateCache($event->page));
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
        $class = "ZEDx\Listeners\PageEventListener";

        $events->listen('ZEDx\Events\Page\PageTemplateWasSwitched', $class.'@onPageTemplateSwitch');
        $events->listen('ZEDx\Events\Page\PageThemepartialWasAttached', $class.'@onPageThemepartialAttach');
        $events->listen('ZEDx\Events\Page\PageThemepartialWasDetached', $class.'@onPageThemepartialDetach');
        $events->listen('ZEDx\Events\Page\PageWasCreated', $class.'@onPageCreate');
        $events->listen('ZEDx\Events\Page\PageWasDeleted', $class.'@onPageDelete');
        $events->listen('ZEDx\Events\Page\PageWasUpdated', $class.'@onPageUpdate');
    }
}
