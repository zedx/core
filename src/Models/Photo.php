<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ['path', 'is_main'];

    protected $casts = [
    'is_main' => 'boolean',
  ];

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    public function scopeMain($query)
    {
        return $query->whereIsMain(true);
    }

    public function scopeOthers($query)
    {
        return $query->whereIsMain(false);
    }
}
