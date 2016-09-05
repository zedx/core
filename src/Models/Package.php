<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'type', 'namespace',
        'version', 'updated_at'
    ];

    protected $casts = [
        'updated_at' => 'date',
    ];
}
