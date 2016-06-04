<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;
use ZEDx\Events\Adtype\AdtypeWasCreated;
use ZEDx\Events\Adtype\AdtypeWasDeleted;
use ZEDx\Events\Adtype\AdtypeWasUpdated;
use ZEDx\Events\Adtype\AdtypeWillBeCreated;
use ZEDx\Events\Adtype\AdtypeWillBeDeleted;
use ZEDx\Events\Adtype\AdtypeWillBeUpdated;

class Adtype extends Model
{
    protected $fillable = [
    'title', 'is_headline', 'can_renew', 'can_edit', 'is_customized',
    'can_update_pic', 'nbr_pic', 'nbr_days', 'can_add_video',
    'nbr_video', 'can_update_video', 'price', 'can_add_pic',
  ];

    protected $casts = [
      'is_headline'      => 'boolean',
      'can_renew'        => 'boolean',
      'can_edit'         => 'boolean',
      'can_update_pic'   => 'boolean',
      'can_add_video'    => 'boolean',
      'can_update_video' => 'boolean',
      'can_add_pic'      => 'boolean',
  ];

    public function ads()
    {
        return $this->hasMany(Ad::class);
    }

    public function subscriptions()
    {
        return $this->belongsToMany(Subscription::class)->withPivot('number', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('number', 'id');
    }

    public static function all($columns = [])
    {
        return parent::notCustomized()->get();
    }

    public function scopeNotCustomized($query)
    {
        return $query->whereIsCustomized(0);
    }

    public function scopeCustomized($query)
    {
        return $query->whereIsCustomized(1);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($adtype) {
            event(new AdtypeWillBeCreated($adtype));
        });

        static::created(function ($adtype) {
            event(new AdtypeWasCreated($adtype));
        });

        static::updating(function ($adtype) {
            event(new AdtypeWillBeUpdated($adtype));
        });

        static::updated(function ($adtype) {
            event(new AdtypeWasUpdated($adtype));
        });

        static::deleted(function ($adtype) {
            event(new AdtypeWasDeleted($adtype));
        });

        static::deleting(function ($adtype) {
            event(new AdtypeWillBeDeleted($adtype));
            foreach ($adtype->ads as $ad) {
                $ad->forceDelete();
            }
            $adtype->subscriptions()->detach();
            $adtype->users()->detach();
        });
    }
}
