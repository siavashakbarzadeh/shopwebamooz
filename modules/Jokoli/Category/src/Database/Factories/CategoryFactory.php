<?php

namespace Jokoli\Category\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jokoli\Category\Models\Category;

class CategoryFactory extends Factory
{

    protected $model=Category::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'=>$this->faker->sentence(),
            'slug'=>$this->faker->slug(),
        ];
    }
}
