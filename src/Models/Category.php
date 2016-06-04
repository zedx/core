<?php

namespace ZEDx\Models;

use Baum\Node;
use ZEDx\Events\Category\CategoryWasCreated;
use ZEDx\Events\Category\CategoryWasDeleted;
use ZEDx\Events\Category\CategoryWasMoved;

class Category extends Node
{
    protected $fillable = [
    'name', 'is_private', 'is_visible',
  ];

    protected $casts = [
    'is_private' => 'boolean',
    'is_visible' => 'boolean',
  ];

    public function ads()
    {
        return $this->hasMany(Ad::class);
    }

    public function codes()
    {
        return $this->hasMany(Code::class);
    }

    public function fields()
    {
        return $this->belongsToMany(Field::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($category) {
            event(new CategoryWasCreated($category));
        });

        static::moved(function ($category) {
            event(new CategoryWasMoved($category));
        });

        static::deleted(function ($category) {
            event(new CategoryWasDeleted($category));
        });

        static::deleting(function ($category) {
            foreach ($category->ads as $ad) {
                $ad->forceDelete();
            }
            $category->codes()->delete();
            $category->fields()->detach();
        });
    }
}
