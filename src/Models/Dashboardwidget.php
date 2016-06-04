<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;

class Dashboardwidget extends Model
{
    protected $fillable = [
    'size', 'title', 'namespace',
    'config', 'position',
  ];
}
