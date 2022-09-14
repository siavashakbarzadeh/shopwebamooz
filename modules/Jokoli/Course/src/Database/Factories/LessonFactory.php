<?php

namespace Jokoli\Course\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jokoli\Category\Models\Category;
use Jokoli\Course\Enums\CourseConfirmationStatus;
use Jokoli\Course\Enums\CourseStatus;
use Jokoli\Course\Enums\CourseType;
use Jokoli\Course\Models\Course;
use Jokoli\Course\Models\Lesson;
use Jokoli\Course\Models\Season;
use Jokoli\Media\Models\Media;
use Jokoli\Permission\Enums\Roles;
use Jokoli\User\Models\User;

class LessonFactory extends Factory
{

    protected $model = Lesson::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'media_id'=>Media::factory(),
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
        ];
    }

}
