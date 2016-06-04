<?php

namespace ZEDx\Events\Cache;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Setting;

class CacheSettingWasUpdated extends Event
{
    use SerializesModels;

    public $setting;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }
}
