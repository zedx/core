<?php

namespace ZEDx\Events\Search;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;

class SearchEngineWillRender extends Event
{
    use SerializesModels;

    public $ads;
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($ads, array $data = [])
    {
        $this->ads = $ads;
        $this->data = $data;
    }
}
