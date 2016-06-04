<?php

namespace ZEDx\Events\Adtype;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Adtype;

class AdtypeWasDeleted extends Event
{
    use SerializesModels;

    public $adtype;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Adtype $adtype)
    {
        $this->adtype = $adtype;
    }
}
