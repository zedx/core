<?php

namespace ZEDx\Events\Ad;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Ad;
use ZEDx\Models\Adstatus;

class AdWillBeModerated extends Event
{
    use SerializesModels;

    public $ad;
    public $actor;
    public $adstatus;
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Ad $ad, $actor, Adstatus $adstatus, array $data = [])
    {
        $this->ad = $ad;
        $this->actor = $actor;
        $this->adstatus = $adstatus;
        $this->data = $data;
    }
}
