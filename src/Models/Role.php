<?php

namespace ZEDx\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function admins()
    {
        return $this->hasMany(Admin::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
