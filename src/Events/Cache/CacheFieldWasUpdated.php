<?php

namespace ZEDx\Events\Cache;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Field;

class CacheFieldWasUpdated extends Event
{
    use SerializesModels;

    public $field;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Field $field)
    {
        $this->field = $field;
    }
}
