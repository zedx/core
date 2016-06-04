<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'code', 'name',
    ];
}
