<?php

namespace ZEDx\Events\Search;

use ZEDx\Events\Event;
use Illuminate\Queue\SerializesModels;

class SearchEngineRequested extends Event
{
    use SerializesModels;

    public $params;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->params = $params;
    }
}
