<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
    'item_id', 'data', 'reference',
    'payerId', 'response', 'command',
  ];

    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
