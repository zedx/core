<?php

namespace ZEDx\Events\Ad;

use ZEDx\Events\Event;
use Illuminate\Queue\SerializesModels;
use ZEDx\Models\Ad;

class AdWillBeDisplayed extends Event
{
    use SerializesModels;

    public $ad;
    public $actor;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Ad $ad, $actor = null)
    {
        $this->ad = $ad;
        $this->actor = $actor;
    }
}
