<?php

namespace ZEDx\Events\Cache;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Page;

class CachePageWasUpdated extends Event
{
    use SerializesModels;

    public $page;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Page $page)
    {
        $this->page = $page;
    }
}
