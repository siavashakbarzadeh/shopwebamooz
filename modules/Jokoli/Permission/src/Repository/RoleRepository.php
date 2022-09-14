<?php

namespace Jokoli\Permission\Repository;

use Illuminate\Database\Eloquent\Builder;
use Jokoli\Permission\Http\Requests\RoleRequest;
use Jokoli\Permission\Models\Role;

class RoleRepository
{
    private function query()
    {
        return Role::query();
    }

    public function findOrFailById($role)
    {
        return $this->query()->findOrFail($role);
    }

    public function all($withPermissions = false)
    {
        return $this->query()->when($withPermissions, function (Builder $builder) {
            $builder->with('permissions');
        })->get();
    }

    public function store(RoleRequest $request)
    {
        return $this->query()->create(['name' => $request->name])->syncPermissions($request->permissions);
    }

    public function update($role, RoleRequest $request)
    {
        $role->update([
            'name' => $request->name,
        ]);
        $role->syncPermissions($request->permissions);
    }

    public function destroy($role)
    {
        $role->delete();
    }
}
