<?php

namespace ZEDx\Events\Ad;

use ZEDx\Models\Ad;
use ZEDx\Events\Event;
use Illuminate\Queue\SerializesModels;

class AdWillBeDeleted extends Event
{
    use SerializesModels;

    public $ad;
    public $actor;
    public $forceDelete;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Ad $ad, $actor, $forceDelete = false)
    {
        $this->ad = $ad;
        $this->actor = $actor;
        $this->forceDelete = $forceDelete;
    }
}
