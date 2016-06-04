<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;

class Adcontent extends Model
{
    protected $fillable = [
    'body', 'title',
  ];

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }
}
