<?php

namespace ZEDx\Events\Setting;

use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Event;
use ZEDx\Models\Setting;

class SettingWillBeUpdated extends Event
{
    use SerializesModels;

    public $setting;
    public $actor;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Setting $setting, $actor)
    {
        $this->setting = $setting;
        $this->actor = $actor;
    }
}
