<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
    'status', 'name', 'quantity',
    'gateway', 'driver', 'amount',
    'user_id',
  ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
