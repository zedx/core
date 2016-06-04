<?php

namespace ZEDx\Events\Ip;

use ZEDx\Events\Event;
use Illuminate\Queue\SerializesModels;

class IpWillBeBlacklisted extends Event
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
