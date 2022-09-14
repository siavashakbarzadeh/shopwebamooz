<?php

namespace Jokoli\Course\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jokoli\Category\Models\Category;
use Jokoli\Course\Enums\CourseConfirmationStatus;
use Jokoli\Course\Enums\CourseStatus;
use Jokoli\Course\Enums\CourseType;
use Jokoli\Course\Models\Course;
use Jokoli\Course\Models\Season;
use Jokoli\Permission\Enums\Roles;
use Jokoli\User\Models\User;

class CourseFactory extends Factory
{

    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'teacher_id' => User::factory()->create()->assignRole(Roles::Teacher),
            'category_id' => Category::factory(),
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
            'price' => $this->faker->randomElement([100000, 200000, 250000, 300000, 500000]),
            'percent' => $this->faker->numberBetween(1, 100),
            'type' => CourseType::Cash,
            'status' => CourseStatus::Completed,
            'confirmation_status' => CourseConfirmationStatus::Pending,
            'body' => $this->faker->text(),
        ];
    }

    public function free()
    {
        return $this->state(['type' => CourseType::Free, 'price' => 0]);
    }

    public function uncompleted()
    {
        return $this->state(['status' => CourseStatus::Uncompleted]);
    }

    public function locked()
    {
        return $this->state(['status' => CourseStatus::Locked]);
    }

    public function accepted()
    {
        return $this->state(['confirmation_status' => CourseConfirmationStatus::Accepted]);
    }

    public function rejected()
    {
        return $this->state(['confirmation_status' => CourseConfirmationStatus::Rejected]);
    }

    public function season()
    {
        return $this->afterCreating(function (Course $course) {
            Season::factory()->state(['course_id' => $course->id, 'user_id' => 1])->lesson()->create();
        });
    }
}
