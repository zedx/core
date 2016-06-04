<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class Country extends Model
{
    use Eloquence;

    protected $fillable = [
    'is_activate',
  ];

    protected $casts = [
    'is_activate' => 'boolean',
  ];

  /**
   * Searchable rules.
   *
   * @var array
   */
  protected $searchableColumns = [
    'code'     => 10,
    'en'       => 5,
    'currency' => 5,
  ];

    public function scopeEnabled($query)
    {
        return $query->whereIsActivate('1');
    }
}
