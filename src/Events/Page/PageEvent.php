<?php

namespace ZEDx\Events\Page;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Page;

class PageEvent extends Event
{
    use SerializesModels;

    /**
     * Page model.
     *
     * @var Page
     */
    public $page;

    /**
     * Create a new event instance.
     *
     * @param \ZEDx\Models\Page $page
     *
     * @return void
     */
    public function __construct(Page $page)
    {
        $this->page = $page;
    }
}
