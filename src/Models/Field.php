<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;
use ZEDx\Events\Field\FieldWasDeleted;

class Field extends Model
{
    protected $fillable = [
        'name', 'type', 'status', 'title', 'unit',
        'is_price', 'is_in_ads_list', 'is_in_ad',
        'is_in_search', 'is_format',
    ];

    protected $casts = [
        'is_price'       => 'boolean',
        'is_in_ads_list' => 'boolean',
        'is_in_ad'       => 'boolean',
        'is_in_search'   => 'boolean',
        'is_format'      => 'boolean',
    ];

    public function search()
    {
        return $this->hasOne(SearchField::class)->select(['field_id', 'min', 'max', 'step', 'is_smart']);
    }

    public function select()
    {
        return $this->hasMany(SelectField::class);
    }

    public function ads()
    {
        return $this->belongsToMany(Ad::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($field) {
            event(
                new FieldWasDeleted($field)
            );
        });

        static::deleting(function ($field) {
            $field->select()->delete();
            $field->search()->delete();
            $field->ads()->detach();
            $field->categories()->detach();
        });
    }
}
