<?php

namespace Jokoli\Permission\Database\Seeders;

use Illuminate\Database\Seeder;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\Permission\Enums\Roles;
use Jokoli\Permission\Models\Permission;
use Jokoli\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            Roles::SupperAdmin => [
                Permissions::Teach
            ],
            Roles::Student => [],
            Roles::Teacher => [
                Permissions::Teach,
                Permissions::ManageOwnCourses,
            ],
        ];
        foreach ($roles as $name => $permissions) {
            Role::findOrCreate($name)->syncPermissions($permissions);
        }
    }
}
