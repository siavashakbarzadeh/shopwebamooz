<?php

namespace Jokoli\Course\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jokoli\Category\Models\Category;
use Jokoli\Course\Enums\CourseConfirmationStatus;
use Jokoli\Course\Enums\CourseStatus;
use Jokoli\Course\Enums\CourseType;
use Jokoli\Course\Enums\LessonStatus;
use Jokoli\Course\Enums\SeasonConfirmationStatus;
use Jokoli\Course\Models\Course;
use Jokoli\Course\Models\Lesson;
use Jokoli\Course\Models\Season;
use Jokoli\Media\Models\Media;
use Jokoli\Permission\Enums\Roles;
use Jokoli\User\Models\User;

class SeasonFactory extends Factory
{

    protected $model = Season::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(),
            'confirmation_status' => SeasonConfirmationStatus::Accepted,
        ];
    }

    public function lesson($count=1,$status=LessonStatus::Opened)
    {
        return $this->afterCreating(function (Season $season)use ($count,$status) {
            Lesson::factory()->state(['user_id' => $season->user_id,'season_id'=>$season->id,'course_id'=>$season->course_id,'status'=>$status])->count($count)->create();
        });
    }

}
