<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = ['link'];

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }
}
