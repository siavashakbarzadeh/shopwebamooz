<?php

namespace Jokoli\Permission\Repository;

use Jokoli\Permission\Models\Permission;

class PermissionRepository
{
    private function query()
    {
        return Permission::query();
    }

    public function all()
    {
        return $this->query()->get();
    }
}
