<?php

namespace ZEDx\Jobs;

use Cache;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ZEDx\Events\Cache\CacheAdminWasUpdated;
use ZEDx\Events\Cache\CacheAdtypeWasUpdated;
use ZEDx\Events\Cache\CacheAdWasUpdated;
use ZEDx\Events\Cache\CacheCategoryWasUpdated;
use ZEDx\Events\Cache\CacheFieldWasUpdated;
use ZEDx\Events\Cache\CacheIpWasUpdated;
use ZEDx\Events\Cache\CacheMenuWasUpdated;
use ZEDx\Events\Cache\CachePageWasUpdated;
use ZEDx\Events\Cache\CacheSettingWasUpdated;
use ZEDx\Events\Cache\CacheSubscriptionWasUpdated;
use ZEDx\Events\Cache\CacheTemplateWasUpdated;
use ZEDx\Events\Cache\CacheUserWasUpdated;

class UpdateCache extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $model;

    public $deleted;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($model, $deleted = false)
    {
        $this->model = $model;
        $this->deleted = $deleted;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $modelClass = get_class($this->model);

        if ($modelClass == 'ZEDx\Models\Admin') {
            return $this->updateAdminCache($this->model);
        }

        if ($modelClass == 'ZEDx\Models\Adtype') {
            return $this->updateAdtypeCache($this->model);
        }

        if ($modelClass == 'ZEDx\Models\Ip') {
            return $this->updateIpCache($this->model);
        }

        if ($modelClass == 'ZEDx\Models\Menu') {
            return $this->updateMenuCache($this->model);
        }

        if ($modelClass == 'ZEDx\Models\Page') {
            return $this->updatePageCache($this->model);
        }

        if ($modelClass == 'ZEDx\Models\Setting') {
            return $this->updateSettingCache($this->model);
        }

        if ($modelClass == 'ZEDx\Models\Subscription') {
            return $this->updateSubscriptionCache($this->model);
        }

        if ($modelClass == 'ZEDx\Models\Template') {
            return $this->updateTemplateCache($this->model);
        }

        if ($modelClass == 'ZEDx\Models\User') {
            return $this->updateUserCache($this->model);
        }

        if ($modelClass == 'ZEDx\Models\Ad') {
            return $this->updateAdCache($this->model);
        }

        if ($modelClass == 'ZEDx\Models\Category') {
            return $this->updateCategoryCache($this->model);
        }

        if ($modelClass == 'ZEDx\Models\Field') {
            return $this->updateFieldCache($this->model);
        }
    }

    /**
     * Update Admin Cache.
     */
    private function updateAdminCache($admin)
    {
        $this->updateCacheField($admin);

        event(
            new CacheAdminWasUpdated($admin, $this->deleted)
        );
    }

    /**
     * Update Adtype Cache.
     */
    private function updateAdtypeCache($adtype)
    {
        foreach ($adtype->ads as $ad) {
            $this->updateAdCache($ad);
        }

        $this->updateCacheField($adtype);

        event(
            new CacheAdtypeWasUpdated($adtype, $this->deleted)
        );
    }

    /**
     * Update Ip Cache.
     */
    private function updateIpCache($ip)
    {
        $this->updateCacheField($ip);

        event(
            new CacheIpWasUpdated($ip, $this->deleted)
        );
    }

    /**
     * Update Menu Cache.
     */
    private function updateMenuCache($menu)
    {
        $this->updateCacheField($menu);

        event(
            new CacheMenuWasUpdated($menu, $this->deleted)
        );
    }

    /**
     * Update Page Cache.
     */
    private function updatePageCache($page)
    {
        $this->updateCacheField($page);

        event(
            new CachePageWasUpdated($page, $this->deleted)
        );
    }

    /**
     * Update Setting Cache.
     */
    private function updateSettingCache($setting)
    {
        $this->updateCacheField($setting);

        event(
            new CacheSettingWasUpdated($setting, $this->deleted)
        );
    }

    /**
     * Update Subscription Cache.
     */
    private function updateSubscriptionCache($subscription)
    {
        foreach ($adtype->users as $user) {
            $this->updateUserCache($user);
        }

        $this->updateCacheField($subscription);

        event(
            new CacheSubscriptionWasUpdated($subscription, $this->deleted)
        );
    }

    /**
     * Update Template Cache.
     */
    private function updateTemplateCache($template)
    {
        foreach ($template->pages as $page) {
            $this->updatePageCache($page);
        }

        $this->updateCacheField($template);

        event(
            new CacheTemplateWasUpdated($template, $this->deleted)
        );
    }

    /**
     * Update User Cache.
     */
    private function updateUserCache($user)
    {
        $this->updateCacheField($user);

        event(
            new CacheUserWasUpdated($user, $this->deleted)
        );
    }

    /**
     * Update Ad Cache.
     */
    private function updateAdCache($ad)
    {
        $this->updateCacheField($ad);

        event(
            new CacheAdWasUpdated($ad, $this->deleted)
        );
    }

    /**
     * Update Category Cache.
     */
    private function updateCategoryCache($category)
    {
        foreach ($category->ads as $ad) {
            $this->updateAdCache($ad);
        }

        $this->updateCacheField($category);

        event(
            new CacheCategoryWasUpdated($category, $this->deleted)
        );
    }

    /**
     * Update Field Cache.
     */
    private function updateFieldCache($field)
    {
        foreach ($field->ads as $ad) {
            $this->updateAdCache($ad);
        }

        foreach ($field->categories as $category) {
            $this->updateCategoryCache($category);
        }

        $this->updateCacheField($field);

        event(
            new CacheFieldWasUpdated($field, $this->deleted)
        );
    }

    private function updateCacheField($model)
    {
        if ($this->deleted) {
            return;
        }

        $model->cached_at = Carbon::now();
        $model->save(['timestamps' => false]);
    }
}
