<?php

namespace Jokoli\Permission\Tests\Feature;

use Jokoli\Permission\Enums\Permissions;
use Jokoli\Permission\Enums\Roles;
use Jokoli\Permission\Models\Permission;
use Jokoli\Permission\Models\Role;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    public function test_permitted_user_can_see_role_permissions()
    {
        $this->actingAsUserWithPermission(Permissions::ManagePermissions)
            ->get(route('permissions.index'))->assertOk();
        $this->actingAsUserWithRole(Roles::SupperAdmin)
            ->get(route('permissions.index'))->assertOk();
    }

    public function test_normal_user_can_not_see_role_permissions()
    {
        $this->actingAsUser()
            ->get(route('permissions.index'))->assertForbidden();
    }

    public function test_permitted_user_can_store_roles()
    {
        $name = $this->faker->sentence();
        $this->assertDatabaseMissing(Role::class, ['name' => $name])
            ->actingAsUserWithPermission(Permissions::ManagePermissions)
            ->post(route('permissions.store'), [
                'name' => $name,
                'permissions' => Permission::query()->inRandomOrder()->limit(3)->pluck('id')->toArray(),
            ])->assertRedirect(route('permissions.index'));
        $this->assertDatabaseHas(Role::class, ['name' => $name]);

        $name = $this->faker->sentence();
        $this->assertDatabaseMissing(Role::class, ['name' => $name])
            ->actingAsUserWithRole(Roles::SupperAdmin)
            ->post(route('permissions.store'), [
                'name' => $name,
                'permissions' => Permission::query()->inRandomOrder()->limit(3)->pluck('id')->toArray(),
            ])->assertRedirect(route('permissions.index'));
        $this->assertDatabaseHas(Role::class, ['name' => $name]);
    }

    public function test_normal_user_can_not_store_roles()
    {
        $name = $this->faker->sentence();
        $this->assertDatabaseMissing(Role::class, ['name' => $name])
            ->actingAsUser()
            ->post(route('permissions.store'), [
                'name' => $name,
                'permissions' => Permission::query()->inRandomOrder()->limit(3)->pluck('id')->toArray(),
            ])->assertForbidden();
        $this->assertDatabaseMissing(Role::class, ['name' => $name]);
    }

    public function test_permitted_user_can_edit_roles()
    {
        Role::factory()->has(Permission::factory()->count(5))->create();
        $this->actingAsUserWithPermission(Permissions::ManagePermissions)
            ->get(route('permissions.edit', 1))->assertOk();
        $this->actingAsUserWithRole(Roles::SupperAdmin)
            ->get(route('permissions.edit', 1))->assertOk();
    }

    public function test_normal_user_can_not_edit_roles()
    {
        Role::factory()->has(Permission::factory()->count(5))->create();
        $this->actingAsUser()
            ->get(route('permissions.edit', 1))->assertForbidden();
    }

    public function test_permitted_user_can_update_roles()
    {
        $role = Role::factory()->has(Permission::factory()->count(5))->create();
        $this->actingAsUserWithPermission(Permissions::ManagePermissions)
            ->patch(route('permissions.update', $role->id), [
                'name' => $this->faker->sentence,
                'permissions' => Permission::query()->inRandomOrder()->limit(3)->pluck('id')->toArray(),
            ])->assertStatus(302)->assertRedirect(route('permissions.index'));
        $this->assertNotEquals($role->name, $role->refresh()->name);
        $this->assertEquals(3, $role->permissions->count());

        $this->actingAsUserWithRole(Roles::SupperAdmin)
            ->patch(route('permissions.update', $role->id), [
                'name' => $this->faker->sentence,
                'permissions' => Permission::query()->inRandomOrder()->limit(2)->pluck('id')->toArray(),
            ])->assertStatus(302)->assertRedirect(route('permissions.index'));
        $this->assertNotEquals($role->name, $role->refresh()->name);
        $this->assertEquals(2, $role->permissions->count());
    }

    public function test_normal_user_can_not_update_roles()
    {
        $this->actingAsUser()
            ->patch(route('permissions.update', 1),[
                'name' => $this->faker->sentence,
                'permissions' => Permission::query()->inRandomOrder()->limit(2)->pluck('id')->toArray(),
            ])->assertForbidden();
    }

}
