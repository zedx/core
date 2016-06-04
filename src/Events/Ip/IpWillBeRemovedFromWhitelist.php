<?php

namespace ZEDx\Events\Ip;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;

class IpWillBeRemovedFromWhitelist extends Event
{
    use SerializesModels;

    public $actorName;
    public $actorId;
    public $actorRole;
    public $ip;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($actorName, $actorId, $actorRole, $ip)
    {
        $this->actorName = $actorName;
        $this->actorId = $actorId;
        $this->actorRole = $actorRole;
        $this->ip = $ip;
    }
}
