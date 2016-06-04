<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    protected $fillable = [
    'name', 'title', 'driver',
    'options', 'enabled',
  ];

    protected $casts = [
      'enabled' => 'boolean',
  ];

    public function scopeEnabled()
    {
        return $this->whereEnabled(1);
    }
}
