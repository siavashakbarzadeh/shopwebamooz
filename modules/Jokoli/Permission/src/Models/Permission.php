<?php

namespace Jokoli\Permission\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Jokoli\Permission\Database\Factories\PermissionFactory;
use Jokoli\Permission\Enums\Permissions;

class Permission extends \Spatie\Permission\Models\Permission
{
    use HasFactory;

    const Factory = PermissionFactory::class;

    public function getNameFaAttribute()
    {
        return Permissions::getDescription($this->attributes['name']);
    }
}
