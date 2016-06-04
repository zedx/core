<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;
use Rutorika\Sortable\SortableTrait;

class Widgetnode extends Model
{
    use SortableTrait;

    protected $fillable = [
        'templateblock_id', 'namespace', 'title',
        'config', 'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function getConfigAttribute($value)
    {
        return json_decode($value, true);
    }

    public function block()
    {
        return $this->belongsTo(Templateblock::class, 'templateblock_id');
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
