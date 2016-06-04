<?php

namespace ZEDx\Events\Ad;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Ad;
use ZEDx\Models\Adcontent;
use ZEDx\Models\Geolocation;

class AdWillBeCreated extends Event
{
    use SerializesModels;

    public $ad;
    public $content;
    public $geolocation;
    public $actor;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Ad $ad, Adcontent $content, Geolocation $geolocation, $actor)
    {
        $this->ad = $ad;
        $this->content = $content;
        $this->geolocation = $geolocation;
        $this->actor = $actor;
    }
}
