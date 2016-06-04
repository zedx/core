<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;

class Reason extends Model
{
    protected $fillable = ['title'];

    public function ads()
    {
        return $this->belongsToMany(Ad::class);
    }
}
