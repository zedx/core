<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;

class Geolocation extends Model
{
    protected $fillable = [
    'country', 'location_lat', 'location_lng',
    'southwest_lat', 'southwest_lng', 'northeast_lat',
    'northeast_lng', 'radius', 'formatted_address', 'json',
  ];

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }
}
