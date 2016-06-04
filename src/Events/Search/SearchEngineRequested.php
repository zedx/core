<?php

namespace ZEDx\Events\Search;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;

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
