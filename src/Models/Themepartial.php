<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;

class Themepartial extends Model
{
    protected $fillable = [
    'name', 'title',
  ];

    public function pages()
    {
        return $this->belongsToMany(Page::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($page) {
      $page->pages()->detach();
    });
    }
}
