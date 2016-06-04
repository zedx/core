<?php

namespace ZEDx\Events\Template;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Template;

class TemplateEvent extends Event
{
    use SerializesModels;

    /**
     * Template model.
     *
     * @var Template
     */
    public $template;

    /**
     * Create a new event instance.
     *
     * @param \ZEDx\Models\Template $template
     *
     * @return void
     */
    public function __construct(Template $template)
    {
        $this->template = $template;
    }
}
