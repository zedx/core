<?php

namespace ZEDx\Events\Field;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Field;

class FieldEvent extends Event
{
    use SerializesModels;

    /**
     * Field model.
     *
     * @var Field
     */
    public $field;

    /**
     * Create a new event instance.
     *
     * @param \ZEDx\Models\Field $field
     *
     * @return void
     */
    public function __construct(Field $field)
    {
        $this->field = $field;
    }
}
