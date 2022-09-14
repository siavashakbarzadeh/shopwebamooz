<?php

namespace Jokoli\Course\Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Jokoli\Category\Models\Category;
use Jokoli\Course\Enums\CourseConfirmationStatus;
use Jokoli\Course\Enums\CourseStatus;
use Jokoli\Course\Enums\CourseType;
use Jokoli\Course\Models\Course;
use Jokoli\Course\Repository\CourseRepository;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\Permission\Enums\Roles;
use Jokoli\User\Models\User;
use Tests\TestCase;

class CourseTest extends TestCase
{

    public function test_permitted_user_can_see_courses()
    {
        $repository = resolve(CourseRepository::class);
        $this->actingAsUserWithPermission(Permissions::ManageOwnCourses)
            ->get(route('courses.index'))->assertOk()
            ->assertViewHas('courses', $repository->paginate());

        $this->actingAsUserWithPermission(Permissions::ManageCourses)
            ->get(route('courses.index'))->assertOk()
            ->assertViewHas('courses', $repository->paginate());

        $this->actingAsUserWithRole(Roles::SupperAdmin)
            ->get(route('courses.index'))->assertOk()
            ->assertViewHas('courses', $repository->paginate());
    }

    public function test_normal_user_can_not_see_courses()
    {
        $this->actingAsUser()->get(route('courses.index'))->assertForbidden();
    }

    public function test_permitted_user_can_see_detail_courses()
    {
        Course::factory()->create();
        $this->actingAsById(1)->get(route('courses.details', 1))->assertOk();
        $this->actingAsUserWithPermission(Permissions::ManageCourses)
            ->get(route('courses.details', 1))->assertOk();
        $this->actingAsUserWithRole(Roles::SupperAdmin)
            ->get(route('courses.details', 1))->assertOk();
    }

    public function test_normal_user_can_not_see_detail_courses()
    {
        Course::factory()->create();
        $this->actingAsUser()->get(route('courses.details', 1))->assertForbidden();
    }

    public function test_permitted_user_can_create_courses()
    {
        $this->actingAsUserWithPermission(Permissions::ManageOwnCourses)
            ->get(route('courses.create'))
            ->assertOk();

        $this->actingAsUserWithPermission(Permissions::ManageCourses)
            ->get(route('courses.create'))
            ->assertOk();

        $this->actingAsUserWithRole(Roles::SupperAdmin)
            ->get(route('courses.create'))
            ->assertOk();
    }

    public function test_normal_user_can_not_create_courses()
    {
        $this->actingAsUser()
            ->get(route('courses.create'))
            ->assertForbidden();
    }

    public function test_permitted_user_can_store_courses()
    {
        Storage::fake('public');
        User::factory()->create()->assignRole(Roles::Teacher);
        Category::factory()->create();
        $this->assertDatabaseCount(Course::class, 0)
            ->actingAsUserWithPermission(Permissions::ManageOwnCourses)
            ->post(route('courses.store'), $this->array_course_data())
            ->assertStatus(302)
            ->assertRedirect(route('courses.index'));
        $course = Course::query()->find(1);
        Storage::disk($course->banner->disk)->assertExists($course->banner->files['original']);
        $this->assertDatabaseCount(Course::class, 1);

        $this->assertDatabaseCount(Course::class, 1)
            ->actingAsUserWithPermission(Permissions::ManageCourses)
            ->post(route('courses.store'), $this->array_course_data())
            ->assertStatus(302)
            ->assertRedirect(route('courses.index'));
        $course = Course::query()->find(2);
        Storage::disk($course->banner->disk)->assertExists($course->banner->files['original']);
        $this->assertDatabaseCount(Course::class, 2);

        $this->assertDatabaseCount(Course::class, 2)
            ->actingAsUserWithRole(Roles::SupperAdmin)
            ->post(route('courses.store'), $this->array_course_data())
            ->assertStatus(302)
            ->assertRedirect(route('courses.index'));
        $course = Course::query()->find(3);
        Storage::disk($course->banner->disk)->assertExists($course->banner->files['original']);
        $this->assertDatabaseCount(Course::class, 3);
    }

    public function test_normal_user_can_not_store_courses()
    {
        Storage::fake();
        User::factory()->create()->assignRole(Roles::Teacher);
        Category::factory()->create();
        $this->actingAsUser()
            ->post(route('courses.store'), $this->array_course_data())
            ->assertForbidden();
    }

    public function test_permitted_user_can_edit_courses()
    {
        $course = Course::factory()->create();
        $this->actingAsById($course->teacher_id)
            ->get(route('courses.edit', 1))->assertOk();

        $this->actingAsUserWithPermission(Permissions::ManageCourses)
            ->get(route('courses.edit', 1))->assertOk();

        $this->actingAsUserWithRole(Roles::SupperAdmin)
            ->get(route('courses.edit', 1))->assertOk();
    }

    public function test_normal_user_can_not_edit_courses()
    {
        Course::factory()->create();
        $this->actingAsUser()
            ->get(route('courses.edit', 1))->assertForbidden();
    }

    public function test_user_has_own_courses_permission_can_not_edit_other_teachers_courses()
    {
        Course::factory()->create();
        $this->actingAsUserWithPermission(Permissions::ManageOwnCourses)
            ->get(route('courses.edit', 1))->assertForbidden();
    }

    public function test_permitted_user_can_update_courses()
    {
        Storage::fake('public');
        $course = Course::factory()->create();
        $title = $this->faker->unique()->sentence();
        $this->assertDatabaseMissing(Course::class, ['title' => $title])
            ->actingAsById($course->teacher_id)
            ->patch(route('courses.update', 1), $this->array_course_data(['title' => $title]));
        $this->assertDatabaseHas(Course::class, ['title' => $title]);
        Storage::disk($course->refresh()->banner->disk)->assertExists($course->refresh()->banner->files['original']);
        $title = $this->faker->unique()->sentence();
        $this->assertDatabaseMissing(Course::class, ['title' => $title])
            ->actingAsUserWithPermission(Permissions::ManageCourses)
            ->patch(route('courses.update', 1), $this->array_course_data(['title' => $title]));
        $this->assertDatabaseHas(Course::class, ['title' => $title]);
        Storage::disk($course->refresh()->banner->disk)->assertExists($course->refresh()->banner->files['original']);

        $title = $this->faker->unique()->sentence();
        $this->assertDatabaseMissing(Course::class, ['title' => $title])
            ->actingAsUserWithRole(Roles::SupperAdmin)
            ->patch(route('courses.update', 1), $this->array_course_data(['title' => $title]));
        $this->assertDatabaseHas(Course::class, ['title' => $title]);
        Storage::disk($course->refresh()->banner->disk)->assertExists($course->refresh()->banner->files['original']);
    }

    public function test_normal_user_can_not_update_courses()
    {
        Course::factory()->create();

        $title = $this->faker->unique()->sentence();
        $this->actingAsUser()
            ->patch(route('courses.update', 1), $this->array_course_data(['title' => $title]))
            ->assertForbidden();
    }

    public function test_user_has_own_courses_permission_can_not_update_other_teachers_courses()
    {
        Course::factory()->create();
        $title = $this->faker->unique()->sentence();
        $this->actingAsUserWithPermission(Permissions::ManageOwnCourses)
            ->patch(route('courses.update', 1), $this->array_course_data(['title' => $title]))
            ->assertForbidden();
    }

    public function test_permitted_user_can_destroy_courses()
    {
        Course::factory()->count(2)->create();
        $this->assertDatabaseCount(Course::class, 2)
            ->actingAsUserWithPermission(Permissions::ManageCourses)
            ->delete(route('courses.destroy', 1))
            ->assertOk();
        $this->assertDatabaseCount(Course::class, 1);

        $this->assertDatabaseCount(Course::class, 1)
            ->actingAsUserWithRole(Roles::SupperAdmin)
            ->delete(route('courses.destroy', 2))
            ->assertOk();
        $this->assertDatabaseCount(Course::class, 0);
    }

    public function test_normal_user_can_not_destroy_courses()
    {
        Course::factory()->create();
        $this->assertDatabaseCount(Course::class, 1)
            ->actingAsUser()
            ->delete(route('courses.destroy', 1))
            ->assertForbidden();
        $this->assertDatabaseCount(Course::class, 1);
    }

    public function test_permitted_user_can_accept_courses()
    {
        Course::factory()->count(2)->create();
        $this->assertDatabaseMissing(Course::class, ['id' => 1, 'confirmation_status' => CourseConfirmationStatus::Accepted])
            ->actingAsUserWithPermission(Permissions::ManageCourses)
            ->patch(route('courses.accept', 1))->assertOk();
        $this->assertDatabaseHas(Course::class, ['id' => 1, 'confirmation_status' => CourseConfirmationStatus::Accepted]);

        $this->assertDatabaseMissing(Course::class, ['id' => 2, 'confirmation_status' => CourseConfirmationStatus::Accepted])
            ->actingAsUserWithRole(Roles::SupperAdmin)
            ->patch(route('courses.accept', 2))->assertOk();
        $this->assertDatabaseHas(Course::class, ['id' => 2, 'confirmation_status' => CourseConfirmationStatus::Accepted]);
    }

    public function test_normal_user_can_not_accept_courses()
    {
        Course::factory()->create();
        $this->actingAsUser()->patch(route('courses.accept', 1))->assertForbidden();
    }

    public function test_permitted_user_can_reject_courses()
    {
        Course::factory()->count(2)->create();
        $this->assertDatabaseMissing(Course::class, ['id' => 1, 'confirmation_status' => CourseConfirmationStatus::Rejected])
            ->actingAsUserWithPermission(Permissions::ManageCourses)
            ->patch(route('courses.reject', 1))->assertOk();
        $this->assertDatabaseHas(Course::class, ['id' => 1, 'confirmation_status' => CourseConfirmationStatus::Rejected]);

        $this->assertDatabaseMissing(Course::class, ['id' => 2, 'confirmation_status' => CourseConfirmationStatus::Rejected])
            ->actingAsUserWithRole(Roles::SupperAdmin)
            ->patch(route('courses.reject', 2))->assertOk();
        $this->assertDatabaseHas(Course::class, ['id' => 2, 'confirmation_status' => CourseConfirmationStatus::Rejected]);
    }

    public function test_normal_user_can_not_reject_courses()
    {
        Course::factory()->create();
        $this->actingAsUser()->patch(route('courses.reject', 1))->assertForbidden();
    }

    public function test_permitted_user_can_lock_courses()
    {
        Course::factory()->count(2)->create();
        $this->assertDatabaseMissing(Course::class, ['id' => 1, 'status' => CourseStatus::Locked])
            ->actingAsUserWithPermission(Permissions::ManageCourses)
            ->patch(route('courses.lock', 1))->assertOk();
        $this->assertDatabaseHas(Course::class, ['id' => 1, 'status' => CourseStatus::Locked]);

        $this->assertDatabaseMissing(Course::class, ['id' => 2, 'status' => CourseStatus::Locked])
            ->actingAsUserWithRole(Roles::SupperAdmin)
            ->patch(route('courses.lock', 2))->assertOk();
        $this->assertDatabaseHas(Course::class, ['id' => 2, 'status' => CourseStatus::Locked]);
    }

    public function test_normal_user_can_not_lock_courses()
    {
        Course::factory()->create();
        $this->actingAsUser()->patch(route('courses.lock', 1))->assertForbidden();
    }

    private function array_course_data($data = []): array
    {
        return array_merge([
            'teacher_id' => 1,
            'category_id' => 1,
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
            'price' => $this->faker->numberBetween(100),
            'percent' => $this->faker->numberBetween(1, 100),
            'type' => CourseType::Cash,
            'status' => CourseStatus::Uncompleted,
            'body' => $this->faker->text(),
            'image' => UploadedFile::fake()->image('banner.jpg'),
        ], $data);
    }

}
