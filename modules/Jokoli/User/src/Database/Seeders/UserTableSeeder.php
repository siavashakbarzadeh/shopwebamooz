<?php

namespace Jokoli\User\Database\Seeders;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\Permission\Enums\Roles;
use Jokoli\User\Models\User;
use Ybazli\Faker\Facades\Faker;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->create_some_users();
        $this->some_users_give_teach_permission();
    }

    private function create_some_users()
    {
        User::factory()->state(['name'=>"علی کشتکار",'email'=>'ali@yahoo.com','mobile'=>'09115059242'])->create();
        User::factory()->count(19)->create();
    }

    private function some_users_give_teach_permission()
    {
        User::query()->find(1)->assignRole(Roles::SupperAdmin);
        foreach (User::query()->where('id', '!=', 1)->inRandomOrder()->limit(5)->get() as $user) {
            $user->assignRole(Roles::Teacher);
        }
    }
}
