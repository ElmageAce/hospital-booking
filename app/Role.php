<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Role extends Model
{
    protected $fillable = ['name', 'slug'];

    public function allRoles(): Collection
    {
        $key = "all-roles";
        return cache()->rememberForever($key, function(){
            return $this->all();
        });
    }

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
