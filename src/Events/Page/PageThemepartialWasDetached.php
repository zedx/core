<?php

namespace ZEDx\Events\Page;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Page;
use ZEDx\Models\Themepartial;

class PageThemepartialWasDetached extends Event
{
    use SerializesModels;

    /**
     * Page model.
     *
     * @var Page
     */
    public $page;

    /**
     * Themepartial model.
     *
     * @var Themepartial
     */
    public $themepartial;

    /**
     * Create a new event instance.
     *
     * @param \ZEDx\Models\Page         $page
     * @param \ZEDx\Models\Themepartial  $themepartial
     *
     * @return  void
     */
    public function __construct(Page $page, Themepartial $themepartial)
    {
        $this->page = $page;
        $this->themepartial = $themepartial;
    }
}
