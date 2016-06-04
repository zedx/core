<?php

namespace ZEDx\Events\Cache;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Ad;

class CacheAdWasUpdated extends Event
{
    use SerializesModels;

    public $ad;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Ad $ad)
    {
        $this->ad = $ad;
    }
}
