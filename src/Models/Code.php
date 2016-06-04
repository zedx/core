<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Code extends Model
{
    protected $fillable = ['code', 'max', 'end_date'];
    protected $dates = ['end_date'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getEndDateAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }
}
