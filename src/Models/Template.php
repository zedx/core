<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;
use ZEDx\Events\Template\TemplateWasDeleted;

class Template extends Model
{
    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function blocks()
    {
        return $this->hasMany(Templateblock::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($template) {
        event(new TemplateWasDeleted($template));
    });

        static::deleting(function ($template) {
      $template->pages()->delete();
      $template->blocks()->delete();
    });
    }
}
