<?php

namespace ZEDx\Events\Ad;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Ad;

class AdWillBePreviewed extends Event
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
        $this->actor = $actor;
        $this->ad = $ad;
    }
}
