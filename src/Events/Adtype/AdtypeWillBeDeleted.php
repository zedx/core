<?php

namespace ZEDx\Events\Adtype;

use ZEDx\Models\Adtype;
use ZEDx\Events\Event;
use Illuminate\Queue\SerializesModels;

class AdtypeWillBeDeleted extends Event
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
