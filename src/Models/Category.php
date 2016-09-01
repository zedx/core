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
    // 'is_visible' => 'boolean',
  ];

    /**
       * Return an key-value array indicating the node's depth with $seperator
       *
       * @return Array
       */
    public static function getNestedList($column, $key = null, $seperator = ' ') {
        $instance = new static;
        $key = $key ?: $instance->getKeyName();
        $depthColumn = $instance->getDepthColumnName();
        $nodes = $instance->newNestedSetQuery()->visible()->get()->toArray();

        return array_combine(array_map(function($node) use($key) {
            return $node[$key];
        }, $nodes), array_map(function($node) use($seperator, $depthColumn, $column) {
            return str_repeat($seperator, $node[$depthColumn]) . $node[$column];
        }, $nodes));
    }

    public function ads()
    {
        return $this->hasMany(Ad::class);
    }

    public function scopeVisible($query)
    {
        return $query->whereIsVisible(true);
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
