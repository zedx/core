<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;
use ZEDx\Events\Page\PageWasDeleted;
use Sofa\Eloquence\Eloquence;

class Page extends Model
{
    use Eloquence;
    
    protected $fillable = [
        'name', 'shortcut',
        'description', 'is_home',
    ];

  /**
   * Searchable rules.
   *
   * @var array
   */
    protected $searchableColumns = [
        'name' => 10,
        'shortcut' => 5,
        'description' => 10,
  ];

    protected $casts = [
        'is_home' => 'boolean',
    ];

    /**
     * Get all of the tags for the post.
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function nodes()
    {
        return $this->hasMany(Widgetnode::class);
    }

    public function themepartials()
    {
        return $this->belongsToMany(Themepartial::class);
    }

    public function beHomepage()
    {
        self::whereIsHome(true)->update(['is_home' => false]);
        $this->update(['is_home' => true]);
    }

    public function scopeHome()
    {
        return $this->whereIsHome(true);
    }

    public function scopeShortcut($query, $shortcut, $integrateCorePages)
    {
        if ($integrateCorePages) {
            return $this->whereShortcut($shortcut);
        }

        return $this->whereType('page')->whereShortcut($shortcut);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($page) {
            event(new PageWasDeleted($page));
        });

        static::deleting(function ($page) {
            $page->nodes()->delete();
            $page->themepartials()->detach();
            $page->tags()->detach();
        });
    }
}
