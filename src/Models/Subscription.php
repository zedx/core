<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use ZEDx\Events\Subscription\SubscriptionWasDeleted;

class Subscription extends Model
{
    use Eloquence;

    protected $fillable = [
        'title', 'description',
        'days', 'is_default', 'price',
    ];

  /**
   * Searchable rules.
   *
   * @var array
   */
  protected $searchableColumns = [
    'title'       => 10,
    'description' => 10,
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
