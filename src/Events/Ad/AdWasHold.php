<?php

namespace ZEDx\Events\Ad;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Ad;

class AdWasHold extends Event
{
    use SerializesModels;

    public $ad;
    public $actor;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Ad $ad, $actor)
    {
        $this->ad = $ad;
        $this->actor = $actor;
    }
}
