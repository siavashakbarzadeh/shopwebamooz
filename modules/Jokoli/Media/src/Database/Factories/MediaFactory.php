<?php

namespace Jokoli\Media\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jokoli\Category\Models\Category;
use Jokoli\Course\Enums\CourseConfirmationStatus;
use Jokoli\Course\Enums\CourseStatus;
use Jokoli\Course\Enums\CourseType;
use Jokoli\Course\Models\Course;
use Jokoli\Course\Models\Lesson;
use Jokoli\Course\Models\Season;
use Jokoli\Media\Enums\MediaType;
use Jokoli\Media\Models\Media;
use Jokoli\Permission\Enums\Roles;
use Jokoli\User\Models\User;

class MediaFactory extends Factory
{

    protected $model = Media::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'filename' => $this->faker->imageUrl,
            'files' => $this->faker->sentence,
            'type' => $this->faker->randomElement(MediaType::asArray()),
            'disk' => $this->faker->randomElement(['private', 'public']),
        ];
    }

}
