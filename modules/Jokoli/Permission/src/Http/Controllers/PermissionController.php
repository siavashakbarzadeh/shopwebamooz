<?php

namespace Jokoli\Permission\Http\Controllers;

use App\Http\Controllers\Controller;
use Jokoli\Common\Responses\AjaxResponses;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\Permission\Http\Requests\RoleRequest;
use Jokoli\Permission\Models\Permission;
use Jokoli\Permission\Models\Role;
use Jokoli\Permission\Repository\PermissionRepository;
use Jokoli\Permission\Repository\RoleRepository;

class PermissionController extends Controller
{
    private $roleRepository;
    private $permissionRepository;

    public function __construct(RoleRepository $roleRepository, PermissionRepository $permissionRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function index()
    {
        $this->authorize('index',Permission::class);
        $roles = $this->roleRepository->all(true);
        $permissions = $this->permissionRepository->all();
        return view('Permission::index', compact('roles', 'permissions'));
    }

    public function store(RoleRequest $request)
    {
        $this->authorize('create',Permission::class);
        $this->roleRepository->store($request);
        return redirect()->route('permissions.index');
    }

    public function edit($role)
    {
        $this->authorize('edit',Permission::class);
        $role = $this->roleRepository->findOrFailById($role);
        $permissions = $this->permissionRepository->all();
        return view('Permission::edit', compact('role', 'permissions'));
    }

    public function update(RoleRequest $request, $role)
    {
        $this->authorize('edit',Permission::class);
        $role = $this->roleRepository->findOrFailById($role);
        $this->roleRepository->update($role, $request);
        return redirect()->route('permissions.index');
    }

    public function destroy($role)
    {
        $this->authorize('delete',Permission::class);
        $role = $this->roleRepository->findOrFailById($role);
        try {
            $this->roleRepository->destroy($role);
            return AjaxResponses::successResponse();
        } catch (\Throwable $e) {
            return AjaxResponses::errorResponse();
        }
    }
}
