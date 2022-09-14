<?php

namespace Jokoli\Permission\Database\Seeders;

use Illuminate\Database\Seeder;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Permissions::asArray() as $permission) {
            Permission::findOrCreate($permission);
        }
    }
}
