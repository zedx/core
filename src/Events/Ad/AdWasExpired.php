<?php

namespace ZEDx\Events\Ad;

use ZEDx\Models\Ad;
use ZEDx\Events\Event;
use Illuminate\Queue\SerializesModels;

class AdWasExpired extends Event
{
    use SerializesModels;

    public $ad;
    public $actor;
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Ad $ad, $actor, array $data = [])
    {
        $this->ad = $ad;
        $this->actor = $actor;
        $this->data = $data;
    }
}
