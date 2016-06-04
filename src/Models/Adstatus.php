<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;

class Adstatus extends Model
{
    public function ads()
    {
        return $this->hasMany(Ad::class);
    }
}
