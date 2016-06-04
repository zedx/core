<?php

namespace ZEDx\Models;

use Carbon\Carbon;
use Sofa\Eloquence\Eloquence;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, Eloquence;

    protected $dates = ['subscribed_at', 'subscription_expired_at'];

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'email', 'password', 'status',
    'name', 'phone', 'is_phone',
    'company', 'siret',
  ];

    protected $casts = [
    'is_phone' => 'boolean',
  ];

  /**
   * Searchable rules.
   *
   * @var array
   */
  protected $searchableColumns = [
    'id'    => 10,
    'name'  => 10,
    'email' => 5,
  ];

  /**
   * The attributes excluded from the model's JSON form.
   *
   * @var array
   */
  protected $hidden = ['password', 'remember_token'];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function getSubscribedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function setSubscribedAtAttribute($value)
    {
        $this->attributes['subscribed_at'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }

    public function ads()
    {
        return $this->hasMany(Ad::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function adtypes()
    {
        return $this->belongsToMany(Adtype::class)->withPivot('number', 'id');
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($user) {
      foreach ($user->ads as $ad) {
          $ad->forceDelete();
      }
      $user->adtypes()->detach();
    });
    }
}
