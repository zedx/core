<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];

  /**
   * Get all of the pages that are assigned this tag.
   */
  public function pages()
  {
      return $this->morphedByMany(Page::class, 'taggable');
  }
}
