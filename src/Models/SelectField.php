<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;
use Rutorika\Sortable\SortableTrait;

class SelectField extends Model
{
    use SortableTrait;

    protected $fillable = ['name', 'position'];

    protected $hidden = ['field_id', 'position'];

    public $timestamps = false;

    public function field()
    {
        return $this->belongsTo(Field::class);
    }
}
