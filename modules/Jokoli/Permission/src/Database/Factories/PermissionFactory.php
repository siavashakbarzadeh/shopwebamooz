<?php

namespace Jokoli\Permission\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jokoli\Permission\Models\Permission;

class PermissionFactory extends Factory
{

    protected $model = Permission::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->sentence,
            'guard_name' => 'web',
        ];
    }
}
