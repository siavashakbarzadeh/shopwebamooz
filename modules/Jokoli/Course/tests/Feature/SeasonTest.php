<?php

namespace Jokoli\Course\Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Jokoli\Category\Models\Category;
use Jokoli\Course\Enums\CourseConfirmationStatus;
use Jokoli\Course\Enums\CourseStatus;
use Jokoli\Course\Enums\CourseType;
use Jokoli\Course\Enums\SeasonConfirmationStatus;
use Jokoli\Course\Enums\SeasonStatus;
use Jokoli\Course\Models\Course;
use Jokoli\Course\Models\Season;
use Jokoli\Course\Repository\CourseRepository;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\Permission\Enums\Roles;
use Jokoli\User\Models\User;
use Tests\TestCase;

class SeasonTest extends TestCase
{
    public function test_permitted_user_can_store_season()
    {
        Course::factory()->create();
        $this->assertDatabaseCount(Season::class, 0);
        $this->actingAsById(1)->post(route('seasons.store', 1), [
            'title' => $this->faker->sentence(),
        ])->assertStatus(302)->assertRedirect(route('courses.details', 1));
        $this->assertDatabaseCount(Season::class, 1);
        $this->actingAsUserWithPermission(Permissions::ManageCourses)->post(route('seasons.store', 1), [
            'title' => $this->faker->sentence(),
        ])->assertStatus(302)->assertRedirect(route('courses.details', 1));
        $this->assertDatabaseCount(Season::class, 2);
        $this->actingAsUserWithRole(Roles::SupperAdmin)->post(route('seasons.store', 1), [
            'title' => $this->faker->sentence(),
        ])->assertStatus(302)->assertRedirect(route('courses.details', 1));
        $this->assertDatabaseCount(Season::class, 3);
    }

    public function test_normal_user_can_not_store_season()
    {
        Course::factory()->create();
        $this->assertDatabaseCount(Season::class, 0);
        $this->actingAsUser()->post(route('seasons.store', 1), [
            'title' => $this->faker->sentence(),
        ])->assertForbidden();
        $this->assertDatabaseCount(Season::class, 0);
    }

    public function test_permitted_user_can_see_edit_season()
    {
        Course::factory()->has(Season::factory())->create();
        $this->actingAsUserWithPermission(Permissions::ManageCourses)
            ->get(route('seasons.edit', 1))->assertOk();
        $this->actingAsUserWithRole(Roles::SupperAdmin)
            ->get(route('seasons.edit', 1))->assertOk();
    }

    public function test_normal_user_can_see_edit_season()
    {
        Course::factory()->has(Season::factory())->create();
        $this->actingAsUser()->get(route('seasons.edit', 1))->assertForbidden();
    }

    public function test_permitted_user_can_see_update_season()
    {
        Course::factory()->has(Season::factory())->create();
        $title = $this->faker->sentence;
        $this->assertDatabaseMissing(Season::class, ['title' => $title])
            ->actingAsById(1)
            ->patch(route('seasons.update', 1), ['title' => $title])
            ->assertStatus(302)
            ->assertRedirect(route('courses.details', 1));
        $this->assertDatabaseHas(Season::class, ['title' => $title]);
        $title = $this->faker->sentence;
        $this->assertDatabaseMissing(Season::class, ['title' => $title])
            ->actingAsUserWithPermission(Permissions::ManageCourses)
            ->patch(route('seasons.update', 1), ['title' => $title])
            ->assertStatus(302)
            ->assertRedirect(route('courses.details', 1));
        $this->assertDatabaseHas(Season::class, ['title' => $title]);
        $title = $this->faker->sentence;
        $this->assertDatabaseMissing(Season::class, ['title' => $title])
            ->actingAsUserWithRole(Roles::SupperAdmin)
            ->patch(route('seasons.update', 1), ['title' => $title])
            ->assertStatus(302)
            ->assertRedirect(route('courses.details', 1));
        $this->assertDatabaseHas(Season::class, ['title' => $title]);
    }

    public function test_normal_user_can_see_update_season()
    {
        Course::factory()->has(Season::factory())->create();
        $this->actingAsUser()->patch(route('seasons.update', 1), [
            'title' => $this->faker->sentence,
        ])->assertForbidden();
    }

    public function test_permitted_user_can_see_destroy_season()
    {
        Course::factory()->has(Season::factory()->count(3))->create();
        $this->assertDatabaseCount(Season::class, 3)
            ->actingAsById(1)
            ->delete(route('seasons.destroy', 3))
            ->assertOk();
        $this->assertDatabaseCount(Season::class, 2)
            ->actingAsUserWithPermission(Permissions::ManageCourses)
            ->delete(route('seasons.destroy', 2))
            ->assertOk();
        $this->assertDatabaseCount(Season::class, 1)
            ->actingAsUserWithRole(Roles::SupperAdmin)
            ->delete(route('seasons.destroy', 1))
            ->assertOk();
        $this->assertDatabaseCount(Season::class, 0);
    }

    public function test_normal_user_can_see_destroy_season()
    {
        Course::factory()->has(Season::factory())->create();
        $this->actingAsUser()
            ->delete(route('seasons.destroy', 1))
            ->assertForbidden();
        $this->assertDatabaseCount(Season::class, 1);
    }

    public function test_permitted_user_can_see_accept_season()
    {
        Course::factory()->has(Season::factory()->state(['user_id' => 1,'confirmation_status'=>SeasonConfirmationStatus::Pending])->count(2))->create();
        $this->assertDatabaseMissing(Season::class, ['id' => 1, 'confirmation_status' => SeasonConfirmationStatus::Accepted])
            ->actingAsUserWithPermission(Permissions::ManageCourses)
            ->patch(route('seasons.accept', 1))
            ->assertOk();
        $this->assertDatabaseHas(Season::class, ['id' => 1, 'confirmation_status' => SeasonConfirmationStatus::Accepted]);
        $this->assertDatabaseMissing(Season::class, ['id' => 2, 'confirmation_status' => SeasonConfirmationStatus::Accepted])
            ->actingAsUserWithRole(Roles::SupperAdmin)
            ->patch(route('seasons.accept', 2))
            ->assertOk();
        $this->assertDatabaseHas(Season::class, ['id' => 2, 'confirmation_status' => SeasonConfirmationStatus::Accepted]);
    }

    public function test_normal_user_can_see_accept_season()
    {
        Course::factory()->has(Season::factory()->state(['confirmation_status'=>SeasonConfirmationStatus::Pending]))->create();
        $this->assertDatabaseMissing(Season::class, ['id' => 1, 'confirmation_status' => SeasonConfirmationStatus::Accepted])
            ->actingAsUser()
            ->patch(route('seasons.accept', 1))
            ->assertForbidden();
        $this->assertDatabaseMissing(Season::class, ['id' => 1, 'confirmation_status' => SeasonConfirmationStatus::Accepted]);
    }

    public function test_permitted_user_can_see_reject_season()
    {
        Course::factory()->has(Season::factory()->count(2))->create();
        $this->assertDatabaseMissing(Season::class, ['id' => 1, 'confirmation_status' => SeasonConfirmationStatus::Rejected])
            ->actingAsUserWithPermission(Permissions::ManageCourses)
            ->patch(route('seasons.reject', 1))
            ->assertOk();
        $this->assertDatabaseHas(Season::class, ['id' => 1, 'confirmation_status' => SeasonConfirmationStatus::Rejected]);
        $this->assertDatabaseMissing(Season::class, ['id' => 2, 'confirmation_status' => SeasonConfirmationStatus::Rejected])
            ->actingAsUserWithRole(Roles::SupperAdmin)
            ->patch(route('seasons.reject', 2))
            ->assertOk();
        $this->assertDatabaseHas(Season::class, ['id' => 2, 'confirmation_status' => SeasonConfirmationStatus::Rejected]);
    }

    public function test_normal_user_can_see_reject_season()
    {
        Course::factory()->has(Season::factory())->create();
        $this->assertDatabaseMissing(Season::class, ['id' => 1, 'confirmation_status' => SeasonConfirmationStatus::Rejected])
            ->actingAsUser()
            ->patch(route('seasons.reject', 1))
            ->assertForbidden();
        $this->assertDatabaseMissing(Season::class, ['id' => 1, 'confirmation_status' => SeasonConfirmationStatus::Rejected]);
    }

    public function test_permitted_user_can_see_lock_season()
    {
        Course::factory()->has(Season::factory()->count(2))->create();
        $this->assertDatabaseMissing(Season::class, ['id' => 1, 'status' => SeasonStatus::Locked])
            ->actingAsUserWithPermission(Permissions::ManageCourses)
            ->patch(route('seasons.lock', 1))
            ->assertOk();
        $this->assertDatabaseHas(Season::class, ['id' => 1, 'status' => SeasonStatus::Locked]);
        $this->assertDatabaseMissing(Season::class, ['id' => 2, 'status' => SeasonStatus::Locked])
            ->actingAsUserWithRole(Roles::SupperAdmin)
            ->patch(route('seasons.lock', 2))
            ->assertOk();
        $this->assertDatabaseHas(Season::class, ['id' => 2, 'status' => SeasonStatus::Locked]);
    }

    public function test_normal_user_can_see_lock_season()
    {
        Course::factory()->has(Season::factory())->create();
        $this->assertDatabaseMissing(Season::class, ['id' => 1, 'status' => SeasonStatus::Locked])
            ->actingAsUser()
            ->patch(route('seasons.lock', 1))
            ->assertForbidden();
        $this->assertDatabaseMissing(Season::class, ['id' => 1, 'status' => SeasonStatus::Locked]);
    }

    public function test_permitted_user_can_see_unlock_season()
    {
        Course::factory()->has(Season::factory()->state(['status'=>SeasonStatus::Locked])->count(2))->create();
        $this->assertDatabaseMissing(Season::class, ['id' => 1, 'status' => SeasonStatus::Opened])
            ->actingAsUserWithPermission(Permissions::ManageCourses)
            ->patch(route('seasons.unlock', 1))
            ->assertOk();
        $this->assertDatabaseHas(Season::class, ['id' => 1, 'status' => SeasonStatus::Opened]);
        $this->assertDatabaseMissing(Season::class, ['id' => 2, 'status' => SeasonStatus::Opened])
            ->actingAsUserWithRole(Roles::SupperAdmin)
            ->patch(route('seasons.unlock', 2))
            ->assertOk();
        $this->assertDatabaseHas(Season::class, ['id' => 2, 'status' => SeasonStatus::Opened]);
    }

    public function test_normal_user_can_see_unlock_season()
    {
        Course::factory()->has(Season::factory()->state(['status'=>SeasonStatus::Locked]))->create();
        $this->assertDatabaseMissing(Season::class, ['id' => 1, 'status' => SeasonStatus::Opened])
            ->actingAsUser()
            ->patch(route('seasons.unlock', 1))
            ->assertForbidden();
        $this->assertDatabaseMissing(Season::class, ['id' => 1, 'status' => SeasonStatus::Opened]);
    }
}
