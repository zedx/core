<?php

namespace ZEDx\Events\Ad;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Ad;

class AdWasBanned extends Event
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
