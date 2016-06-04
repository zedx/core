<?php

namespace ZEDx\Events\Cache;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Template;

class CacheTemplateWasUpdated extends Event
{
    use SerializesModels;

    public $template;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Template $template)
    {
        $this->template = $template;
    }
}
