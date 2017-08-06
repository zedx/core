<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\Eloquence;

class Ad extends Model
{
    use Eloquence, SoftDeletes;

    protected $dates = [
    'published_at',
    'expired_at', 'deleted_at',
  ];

  /**
   * Searchable rules.
   *
   * @var array
   */
  protected $searchableColumns = [
    'content.title' => 10,
    'content.body'  => 5,
  ];

    public function content()
    {
        return $this->hasOne(Adcontent::class);
    }

    public function geolocation()
    {
        return $this->hasOne(Geolocation::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function adstatus()
    {
        return $this->belongsTo(Adstatus::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function adtype()
    {
        return $this->belongsTo(Adtype::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reasons()
    {
        return $this->belongsToMany(Reason::class);
    }

    public function fields()
    {
        return $this->belongsToMany(Field::class)->withPivot('value', 'string', 'date', 'id');
    }

    public function scopePrice()
    {
        return $this->fields()->whereIsPrice('1')->first();
    }

    public function scopeRecents($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeValidate($query)
    {
        return $query->where('adstatus_id', '1');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($ad) {
            if ($ad->forceDeleting) {
                $ad->content()->delete();
                $ad->geolocation()->delete();
                $ad->photos()->delete();
                $ad->videos()->delete();
                $ad->reasons()->detach();
                $ad->fields()->detach();
            }
        });
    }
}
