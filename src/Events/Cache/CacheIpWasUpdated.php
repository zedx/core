<?php

namespace ZEDx\Events\Cache;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Ip;

class CacheIpWasUpdated extends Event
{
    use SerializesModels;

    public $ip;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Ip $ip)
    {
        $this->ip = $ip;
    }
}
