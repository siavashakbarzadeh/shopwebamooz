<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Jokoli\Permission\Database\Seeders\PermissionTableSeeder;
use Jokoli\Permission\Database\Seeders\RoleTableSeeder;
use Jokoli\User\Models\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase, WithFaker;

    const PASSWORD = "123456";

    protected function afterRefreshingDatabase()
    {
        $this->seed([PermissionTableSeeder::class, RoleTableSeeder::class]);
    }

    public function create_some_users($count = 1)
    {
        $this->assertDatabaseCount(User::class, 0);
        User::factory()->count($count)->create();
        $this->assertDatabaseCount(User::class, $count);
    }

    public function actingAsUser(): TestCase
    {
        return $this->actingAs(User::factory()->create());
    }

    public function actingAsById($id): TestCase
    {
        return $this->actingAs(User::query()->findOrFail($id));
    }

    public function actingAsUserWithPermission($permission): TestCase
    {
        return $this->actingAs(User::factory()->create()->givePermissionTo($permission));
    }

    public function actingAsUserWithRole($role): TestCase
    {
        return $this->actingAs(User::factory()->create()->assignRole($role));
    }

}
