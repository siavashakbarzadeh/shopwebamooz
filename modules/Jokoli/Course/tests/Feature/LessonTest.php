<?php

namespace Jokoli\Course\Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Jokoli\Category\Models\Category;
use Jokoli\Course\Enums\CourseConfirmationStatus;
use Jokoli\Course\Enums\CourseStatus;
use Jokoli\Course\Enums\CourseType;
use Jokoli\Course\Enums\LessonConfirmationStatus;
use Jokoli\Course\Enums\LessonStatus;
use Jokoli\Course\Enums\SeasonConfirmationStatus;
use Jokoli\Course\Enums\SeasonStatus;
use Jokoli\Course\Models\Course;
use Jokoli\Course\Models\Lesson;
use Jokoli\Course\Models\Season;
use Jokoli\Course\Repository\CourseRepository;
use Jokoli\Media\Models\Media;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\Permission\Enums\Roles;
use Jokoli\User\Models\User;
use Tests\TestCase;

class LessonTest extends TestCase
{
    public function test_permitted_user_can_see_create_lesson()
    {
        $course = Course::factory()->season()->create();
        $this->actingAsById($course->id)->get(route('lessons.create', $course->id))->assertOk();
        $this->actingAsUserWithPermission(Permissions::ManageCourses)->get(route('lessons.create', $course->id))->assertOk();
        $this->actingAsUserWithRole(Roles::SupperAdmin)->get(route('lessons.create', $course->id))->assertOk();
    }

    public function test_normal_user_can_see_not_create_lesson()
    {
        $course = Course::factory()->season()->create();
        $this->actingAsUser()->get(route('lessons.create', $course->id))->assertForbidden();
    }

    public function test_permitted_user_can_store_lesson()
    {
        Storage::fake('private');
        $course = Course::factory()->has(Season::factory()->state(['user_id' => 1]))->create();
        $this->actingAsById(1)
            ->assertDatabaseCount(Lesson::class, 0)
            ->post(route('lessons.store', $course->id), $this->array_lesson_data())
            ->assertStatus(302)
            ->assertRedirect(route('courses.details', $course->id));
        $lesson = Lesson::query()->find(1);
        Storage::disk($lesson->media->disk)->assertExists($lesson->media->files);

        $this->actingAsUserWithPermission(Permissions::ManageCourses)
            ->assertDatabaseCount(Lesson::class, 1)
            ->post(route('lessons.store', $course->id), $this->array_lesson_data())
            ->assertStatus(302)
            ->assertRedirect(route('courses.details', $course->id));
        $lesson = Lesson::query()->find(2);
        Storage::disk($lesson->media->disk)->assertExists($lesson->media->files);

        $this->actingAsUserWithRole(Roles::SupperAdmin)
            ->assertDatabaseCount(Lesson::class, 2)
            ->post(route('lessons.store', $course->id), $this->array_lesson_data())
            ->assertStatus(302)
            ->assertRedirect(route('courses.details', $course->id));
        $lesson = Lesson::query()->find(3);
        Storage::disk($lesson->media->disk)->assertExists($lesson->media->files);
        $this->assertDatabaseCount(Lesson::class, 3);
    }

    public function test_normal_user_can_not_store_lesson()
    {
        $course = Course::factory()->has(Season::factory()->state(['user_id' => 1]))->create();
        $this->assertDatabaseCount(Lesson::class, 0);
        $this->actingAsUser()->post(route('lessons.store', $course->id), $this->array_lesson_data())->assertForbidden();
        $this->assertDatabaseCount(Lesson::class, 0);
    }

    public function test_permitted_user_can_see_edit_lesson()
    {
        Course::factory()->has(Season::factory()->state(['user_id' => 1])->lesson())->create();
        $this->actingAsUserWithPermission(Permissions::ManageCourses)->get(route('lessons.edit',1))->assertOk();
    }

    public function test_normal_user_can_not_see_edit_lesson()
    {
        Course::factory()->has(Season::factory()->state(['user_id' => 1])->lesson())->create();
        $this->actingAsUser()->get(route('lessons.edit',1))->assertForbidden();
    }

    public function test_permitted_user_can_destroy_lesson()
    {
        Course::factory()->has(Season::factory()->state(['user_id' => 1])->lesson(3))->create();
        $this->actingAsById(1)
            ->assertDatabaseCount(Lesson::class, 3)
            ->delete(route('lessons.destroy', 1))
            ->assertOk();
        $this->actingAsUserWithPermission(Permissions::ManageCourses)
            ->assertDatabaseCount(Lesson::class, 2)
            ->delete(route('lessons.destroy', 2))
            ->assertOk();
        $this->actingAsUserWithRole(Roles::SupperAdmin)
            ->assertDatabaseCount(Lesson::class, 1)
            ->delete(route('lessons.destroy', 3))
            ->assertOk();
        $this->assertDatabaseCount(Lesson::class, 0);
    }

    public function test_normal_user_can_not_destroy_lesson()
    {
        Course::factory()->season()->create();
        $this->actingAsUser()
            ->assertDatabaseCount(Lesson::class, 1)
            ->delete(route('lessons.destroy', 1))
            ->assertForbidden();
        $this->assertDatabaseCount(Lesson::class, 1);
    }

    public function test_permitted_user_can_multi_destroy_lessons()
    {
        Course::factory()->has(Season::factory()->state(['user_id' => 1])->lesson(4))->create();
        $this->actingAsUserWithPermission(Permissions::ManageCourses)
            ->assertDatabaseCount(Lesson::class, 4)
            ->delete(route('lessons.destroyMultiple'), ['ids' => [1, 2]])
            ->assertStatus(302);
        $this->assertDatabaseCount(Lesson::class, 2)
            ->actingAsUserWithRole(Roles::SupperAdmin)
            ->delete(route('lessons.destroyMultiple'), ['ids' => [3, 4]])
            ->assertStatus(302);
        $this->assertDatabaseCount(Lesson::class, 0);
    }

    public function test_normal_user_can_not_multi_destroy_lessons()
    {
        Course::factory()->has(Season::factory()->state(['user_id' => 1])->lesson(2))->create();
        $this->actingAsUser()
            ->assertDatabaseCount(Lesson::class, 2)
            ->delete(route('lessons.destroyMultiple'), ['ids' => [1, 2]])
            ->assertForbidden();
        $this->assertDatabaseCount(Lesson::class, 2);
    }

    public function test_permitted_user_can_accept_all_lessons()
    {
        $course = Course::factory()->has(Season::factory()->state(['user_id' => 1])->lesson(4))->create();
        $this->assertCount(0, Lesson::query()->where('course_id', $course->id)->accepted()->get());
        $this->actingAsUserWithPermission(Permissions::ManageCourses)
            ->patch(route('lessons.acceptAll', $course->id))
            ->assertStatus(302);
        $this->assertCount(4, Lesson::query()->where('course_id', $course->id)->accepted()->get());

        $course = Course::factory()->has(Season::factory()->state(['user_id' => 1])->lesson(4))->create();
        $this->assertCount(0, Lesson::query()->where('course_id', $course->id)->accepted()->get());
        $this->actingAsUserWithRole(Roles::SupperAdmin)
            ->patch(route('lessons.acceptAll', $course->id))
            ->assertStatus(302);
        $this->assertCount(4, Lesson::query()->where('course_id', $course->id)->accepted()->get());
    }

    public function test_normal_user_can_not_accept_all_lessons()
    {
        $course = Course::factory()->has(Season::factory()->state(['user_id' => 1])->lesson(4))->create();
        $this->assertCount(4, Lesson::query()->where('course_id', $course->id)->pending()->get());
        $this->actingAsUser()
            ->patch(route('lessons.acceptAll', $course->id))
            ->assertForbidden();
        $this->assertCount(0, Lesson::query()->where('course_id', $course->id)->accepted()->get());
    }

    public function test_permitted_user_can_multi_accept_lessons()
    {
        Course::factory()->has(Season::factory()->state(['user_id' => 1])->lesson(4))->create();
        $this->assertCount(0, Lesson::query()->whereIn('id', [1, 2])->accepted()->get());
        $this->actingAsUserWithPermission(Permissions::ManageCourses)
            ->patch(route('lessons.acceptMultiple'), ['ids' => [1, 2]])
            ->assertStatus(302);
        $this->assertCount(2, Lesson::query()->whereIn('id', [1, 2])->accepted()->get());

        $this->assertCount(0, Lesson::query()->whereIn('id', [3, 4])->accepted()->get());
        $this->actingAsUserWithRole(Roles::SupperAdmin)
            ->patch(route('lessons.acceptMultiple'), ['ids' => [3, 4]])
            ->assertStatus(302);
        $this->assertCount(2, Lesson::query()->whereIn('id', [3, 4])->accepted()->get());
    }

    public function test_normal_user_can_not_multi_accept_lessons()
    {
        Course::factory()->has(Season::factory()->state(['user_id' => 1])->lesson(2))->create();
        $this->assertCount(0, Lesson::query()->whereIn('id', [1, 2])->accepted()->get());
        $this->actingAsUser()
            ->patch(route('lessons.acceptMultiple'), ['ids' => [1, 2]])
            ->assertForbidden();
        $this->assertCount(0, Lesson::query()->whereIn('id', [1, 2])->accepted()->get());
    }

    public function test_permitted_user_can_multi_reject_lessons()
    {
        Course::factory()->has(Season::factory()->state(['user_id' => 1])->lesson(4))->create();
        $this->assertCount(0, Lesson::query()->whereIn('id', [1, 2])->rejected()->get());
        $this->actingAsUserWithPermission(Permissions::ManageCourses)
            ->patch(route('lessons.rejectMultiple'), ['ids' => [1, 2]])
            ->assertStatus(302);
        $this->assertCount(2, Lesson::query()->whereIn('id', [1, 2])->rejected()->get());

        $this->assertCount(0, Lesson::query()->whereIn('id', [3, 4])->rejected()->get());
        $this->actingAsUserWithRole(Roles::SupperAdmin)
            ->patch(route('lessons.rejectMultiple'), ['ids' => [3, 4]])
            ->assertStatus(302);
        $this->assertCount(2, Lesson::query()->whereIn('id', [3, 4])->rejected()->get());
    }

    public function test_normal_user_can_not_multi_reject_lessons()
    {
        Course::factory()->has(Season::factory()->state(['user_id' => 1])->lesson(2))->create();
        $this->assertCount(0, Lesson::query()->whereIn('id', [1, 2])->rejected()->get());
        $this->actingAsUser()
            ->patch(route('lessons.rejectMultiple'), ['ids' => [1, 2]])
            ->assertForbidden();
        $this->assertCount(0, Lesson::query()->whereIn('id', [1, 2])->rejected()->get());
    }

    public function test_permitted_user_can_reject_lesson()
    {
        Course::factory()->has(Season::factory()->state(['user_id' => 1])->lesson(2))->create();
        $this->actingAsUserWithPermission(Permissions::ManageCourses)
            ->assertDatabaseMissing(Lesson::class, ['id' => 1, 'confirmation_status' => LessonConfirmationStatus::Rejected])
            ->patch(route('lessons.reject',1))
            ->assertOk();
        $this->assertDatabaseHas(Lesson::class, ['id' => 1, 'confirmation_status' => LessonConfirmationStatus::Rejected]);
        $this->actingAsUserWithRole(Roles::SupperAdmin)
            ->assertDatabaseMissing(Lesson::class, ['id' => 2, 'confirmation_status' => LessonConfirmationStatus::Rejected])
            ->patch(route('lessons.reject',2))
            ->assertOk();
        $this->assertDatabaseHas(Lesson::class, ['id' => 2, 'confirmation_status' => LessonConfirmationStatus::Rejected]);
    }

    public function test_normal_user_can_not_reject_lesson()
    {
        Course::factory()->has(Season::factory()->state(['user_id' => 1])->lesson())->create();
        $this->actingAsUser()
            ->assertDatabaseMissing(Lesson::class, ['id' => 1, 'confirmation_status' => LessonConfirmationStatus::Rejected])
            ->patch(route('lessons.reject',1))
            ->assertForbidden();
        $this->assertDatabaseMissing(Lesson::class, ['id' => 1, 'confirmation_status' => LessonConfirmationStatus::Rejected]);
    }

    public function test_permitted_user_can_accept_lesson()
    {
        Course::factory()->has(Season::factory()->state(['user_id' => 1])->lesson(2))->create();
        $this->actingAsUserWithPermission(Permissions::ManageCourses)
            ->assertDatabaseMissing(Lesson::class, ['id' => 1, 'confirmation_status' => LessonConfirmationStatus::Accepted])
            ->patch(route('lessons.accept',1))
            ->assertOk();
        $this->assertDatabaseHas(Lesson::class, ['id' => 1, 'confirmation_status' => LessonConfirmationStatus::Accepted]);
        $this->actingAsUserWithRole(Roles::SupperAdmin)
            ->assertDatabaseMissing(Lesson::class, ['id' => 2, 'confirmation_status' => LessonConfirmationStatus::Accepted])
            ->patch(route('lessons.accept',2))
            ->assertOk();
        $this->assertDatabaseHas(Lesson::class, ['id' => 2, 'confirmation_status' => LessonConfirmationStatus::Accepted]);
    }

    public function test_normal_user_can_not_accept_lesson()
    {
        Course::factory()->has(Season::factory()->state(['user_id' => 1])->lesson())->create();
        $this->actingAsUser()
            ->assertDatabaseMissing(Lesson::class, ['id' => 1, 'confirmation_status' => LessonConfirmationStatus::Accepted])
            ->patch(route('lessons.accept',1))
            ->assertForbidden();
        $this->assertDatabaseMissing(Lesson::class, ['id' => 1, 'confirmation_status' => LessonConfirmationStatus::Accepted]);
    }

    public function test_permitted_user_can_lock_lesson()
    {
        Course::factory()->has(Season::factory()->state(['user_id' => 1])->lesson(2))->create();
        $this->actingAsUserWithPermission(Permissions::ManageCourses)
            ->assertDatabaseMissing(Lesson::class, ['id' => 1, 'status' => LessonStatus::Locked])
            ->patch(route('lessons.lock',1))
            ->assertOk();
        $this->assertDatabaseHas(Lesson::class, ['id' => 1, 'status' => LessonStatus::Locked]);
        $this->actingAsUserWithRole(Roles::SupperAdmin)
            ->assertDatabaseMissing(Lesson::class, ['id' => 2, 'status' => LessonStatus::Locked])
            ->patch(route('lessons.lock',2))
            ->assertOk();
        $this->assertDatabaseHas(Lesson::class, ['id' => 2, 'status' => LessonStatus::Locked]);
    }

    public function test_normal_user_can_not_lock_lesson()
    {
        Course::factory()->has(Season::factory()->state(['user_id' => 1])->lesson())->create();
        $this->actingAsUser()
            ->assertDatabaseMissing(Lesson::class, ['id' => 1, 'status' => LessonStatus::Locked])
            ->patch(route('lessons.lock',1))
            ->assertForbidden();
        $this->assertDatabaseMissing(Lesson::class, ['id' => 1, 'status' => LessonStatus::Locked]);
    }

    public function test_permitted_user_can_unlock_lesson()
    {
        Course::factory()->has(Season::factory()->state(['user_id' => 1])->lesson(2,LessonStatus::Locked))->create();
        $this->actingAsUserWithPermission(Permissions::ManageCourses)
            ->assertDatabaseMissing(Lesson::class, ['id' => 1, 'status' => LessonStatus::Opened])
            ->patch(route('lessons.unlock',1))
            ->assertOk();
        $this->assertDatabaseHas(Lesson::class, ['id' => 1, 'status' => LessonStatus::Opened]);
        $this->actingAsUserWithRole(Roles::SupperAdmin)
            ->assertDatabaseMissing(Lesson::class, ['id' => 2, 'status' => LessonStatus::Opened])
            ->patch(route('lessons.unlock',2))
            ->assertOk();
        $this->assertDatabaseHas(Lesson::class, ['id' => 2, 'status' => LessonStatus::Opened]);
    }

    public function test_normal_user_can_not_unlock_lesson()
    {
        Course::factory()->has(Season::factory()->state(['user_id' => 1])->lesson(1,LessonStatus::Locked))->create();
        $this->actingAsUser()
            ->assertDatabaseMissing(Lesson::class, ['id' => 1, 'status' => LessonStatus::Opened])
            ->patch(route('lessons.unlock',1))
            ->assertForbidden();
        $this->assertDatabaseMissing(Lesson::class, ['id' => 1, 'status' => LessonStatus::Opened]);
    }

    private function array_lesson_data($data = array()): array
    {
        return array_merge([
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
            'duration' => $this->faker->numberBetween(1, 100),
            'season_id' => 1,
            'attachment' => UploadedFile::fake()->create('lesson.zip'),
        ], $data);
    }
}
