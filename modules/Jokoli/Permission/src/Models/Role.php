<?php

namespace Jokoli\Permission\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jokoli\Permission\Database\Factories\RoleFactory;
use Jokoli\Permission\Enums\Roles;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory;

    const Factory = RoleFactory::class;

    public function getNameFaAttribute()
    {
        return Roles::getDescription($this->attributes['name']);
    }


}
