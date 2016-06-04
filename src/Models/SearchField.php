<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;

class SearchField extends Model
{
    protected $hidden = ['field_id'];

    protected $fillable = [
    'min', 'max', 'step', 'is_smart',
  ];

    protected $casts = [
    'is_smart' => 'boolean',
  ];

    public $timestamps = false;

    public function field()
    {
        return $this->belongsTo(Field::class);
    }
}
