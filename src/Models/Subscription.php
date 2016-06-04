<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;
use ZEDx\Events\Subscription\SubscriptionWasDeleted;

class Subscription extends Model
{
    protected $fillable = [
        'title', 'description',
        'days', 'is_default', 'price',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function adtypes()
    {
        return $this->belongsToMany(Adtype::class)->withPivot('number', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($subscription) {
            event(new SubscriptionWasDeleted($subscription));
        });

        static::deleting(function ($subscription) {
            foreach ($subscription->users as $user) {
                $user->delete();
            }
            $subscription->adtypes()->detach();
        });
    }
}
