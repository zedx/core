<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'actor_name', 'actor_id', 'type', 'actor_role',
        'notified_id', 'action', 'notified_name', 'data',
        'is_read', 'is_visible',
    ];

    protected $casts = [
        'is_read'    => 'boolean',
        'is_visible' => 'boolean',
    ];

    public static function readall()
    {
        return self::whereIsRead(false)->update(['is_read' => true]);
    }

    public function scopeNotRead($query)
    {
        return $query->whereIsRead(false);
    }

    public function scopeVisible($query)
    {
        return $query->whereIsVisible(true);
    }

    public function scopeRecents($query)
    {
        return $query->orderBy('is_read', 'asc')->orderBy('created_at', 'desc');
    }
}
