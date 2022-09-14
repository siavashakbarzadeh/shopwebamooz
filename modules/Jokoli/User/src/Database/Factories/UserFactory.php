<?php

namespace Jokoli\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Jokoli\User\Models\User;
use Ybazli\Faker\Facades\Faker;

class UserFactory extends Factory
{
    protected $model = User::class;


    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'mobile' => '0911' . rand(1000000, 9999999),
            'email_verified_at' => now(),
            'password' => '$2y$10$sPonpamycTUaeb/50CKk.uHh7AF4mnnSyQ6FsowK3L1LEP8.0NkyG', // 123456
            'remember_token' => Str::random(10),
        ];
    }

    protected function withFaker()
    {
        return \Faker\Factory::create('fa_IR');
    }


    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
