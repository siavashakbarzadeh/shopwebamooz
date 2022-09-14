<?php

namespace Jokoli\Category\Tests\Feature;

use Illuminate\Http\Response;
use Jokoli\Category\Models\Category;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\Permission\Enums\Roles;
use Jokoli\Permission\Models\Permission;
use Jokoli\User\Models\User;
use Tests\TestCase;

class CategoryTest extends TestCase
{

    public function test_manage_categories_holders_can_see_categories_index()
    {
        $this->actingAsUserWithPermission(Permissions::ManageCategories)
            ->get(route('categories.index'))->assertOk();

        $this->actingAsUserWithRole(Roles::SupperAdmin)->get(route('categories.index'))->assertOk();
    }

    public function test_normal_user_can_see_categories_index()
    {
        $this->actingAsUser()->get(route('categories.index'))->assertForbidden();
    }

    public function test_permitted_user_can_store_categories()
    {
        $this->assertDatabaseCount(Category::class, 0);
        $this->actingAsUserWithPermission(Permissions::ManageCategories)
            ->post(route('categories.store'), [
                'title' => $this->faker->sentence(),
                'slug' => $this->faker->slug(),
            ])->assertStatus(302)->assertRedirect(route('categories.index'));
        $this->assertDatabaseCount(Category::class, 1);

        $this->assertDatabaseCount(Category::class, 1);
        $this->actingAsUserWithRole(Roles::SupperAdmin)
            ->post(route('categories.store'), [
                'title' => $this->faker->sentence(),
                'slug' => $this->faker->slug(),
            ])->assertStatus(302)->assertRedirect(route('categories.index'));
        $this->assertDatabaseCount(Category::class, 2);
    }

    public function test_normal_user_can_not_store_categories()
    {
        $this->assertDatabaseCount(Category::class, 0);
        $this->actingAsUser()
            ->post(route('categories.store'), [
                'title' => $this->faker->sentence(),
                'slug' => $this->faker->slug(),
            ])->assertForbidden();
        $this->assertDatabaseCount(Category::class, 0);
    }

    public function test_permitted_user_can_see_edit_categories()
    {
        Category::factory()->create();
        $this->actingAsUserWithPermission(Permissions::ManageCategories)
            ->get(route('categories.edit', 1))
            ->assertOk();

        $this->actingAsUserWithRole(Roles::SupperAdmin)
            ->get(route('categories.edit', 1))
            ->assertOk();
    }

    public function test_normal_user_can_not_see_edit_categories()
    {
        Category::factory()->create();
        $this->actingAsUser()->get(route('categories.edit', 1))->assertForbidden();
    }

    public function test_permitted_user_can_update_categories()
    {
        Category::factory()->create();
        $sentence = $this->faker->sentence();
        $slug = $this->faker->slug();
        $this->assertDatabaseMissing(Category::class, [
            'title' => $sentence,
            'slug' => $slug,
        ]);
        $this->actingAsUserWithPermission(Permissions::ManageCategories)
            ->patch(route('categories.update', 1), [
            'title' => $sentence,
            'slug' => $slug,
        ])->assertStatus(302)->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas(Category::class, [
            'title' => $sentence,
            'slug' => $slug,
        ]);

        $sentence = $this->faker->sentence();
        $slug = $this->faker->slug();
        $this->assertDatabaseMissing(Category::class, [
            'title' => $sentence,
            'slug' => $slug,
        ]);
        $this->actingAsUserWithRole(Roles::SupperAdmin)
            ->patch(route('categories.update', 1), [
            'title' => $sentence,
            'slug' => $slug,
        ])->assertStatus(302)->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas(Category::class, [
            'title' => $sentence,
            'slug' => $slug,
        ]);
    }

    public function test_normal_user_can_not_update_categories()
    {
        Category::factory()->create();
        $sentence = $this->faker->sentence();
        $slug = $this->faker->slug();
        $this->assertDatabaseMissing(Category::class, [
            'title' => $sentence,
            'slug' => $slug,
        ]);
        $this->actingAsUser()->patch(route('categories.update', 1), [
            'title' => $sentence,
            'slug' => $slug,
        ])->assertForbidden();
        $this->assertDatabaseMissing(Category::class, [
            'title' => $sentence,
            'slug' => $slug,
        ]);
    }

    public function test_permitted_user_can_destroy_categories()
    {
        Category::factory()->count(2)->create();
        $this->assertDatabaseCount(Category::class, 2);
        $this->actingAsUserWithPermission(Permissions::ManageCategories)
            ->delete(route('categories.destroy', 1))
            ->assertOk();
        $this->assertDatabaseCount(Category::class, 1);

        $this->assertDatabaseCount(Category::class, 1);
        $this->actingAsUserWithRole(Roles::SupperAdmin)
            ->delete(route('categories.destroy', 2))->assertOk();
        $this->assertDatabaseCount(Category::class, 0);
    }

    public function test_normal_user_can_not_destroy_categories()
    {
        Category::factory()->create();
        $this->assertDatabaseCount(Category::class, 1);
        $this->actingAsUser()->delete(route('categories.destroy', 1))->assertForbidden();
        $this->assertDatabaseCount(Category::class, 1);
    }

}
