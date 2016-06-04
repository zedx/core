<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;

class Templateblock extends Model
{
    protected $fillable = [
    'identifier', 'title',
  ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function nodes()
    {
        return $this->hasMany(Widgetnode::class);
    }
}
