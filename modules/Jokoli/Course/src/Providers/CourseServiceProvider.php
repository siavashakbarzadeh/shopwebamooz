<?php

namespace Jokoli\Course\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Jokoli\Course\Models\Course;
use Jokoli\Course\Models\Lesson;
use Jokoli\Course\Models\Season;
use Jokoli\Course\Policies\CoursePolicy;
use Jokoli\Course\Policies\LessonPolicy;
use Jokoli\Course\Policies\SeasonPolicy;
use Jokoli\Permission\Enums\Permissions;

class CourseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->_loadMigrations();
        $this->_loadViews();
        $this->_loadTranslations();
    }

    public function boot()
    {
        $this->_sidebar();
        $this->_register_policies();
        $this->_morph_map();
    }

    private function _morph_map()
    {
        Relation::morphMap(['course' => Course::class]);
    }

    private function _loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations/');
    }

    private function _loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'Course');
    }

    private function _sidebar()
    {
        config()->set('sidebar.items.courses', [
            'name' => 'دوره‌ها',
            'icon' => 'i-courses',
            'route' => 'courses.index',
            'routes' => ['courses.*', 'seasons.*', 'lessons.*'],
            'permissions' => [Permissions::ManageCourses, Permissions::ManageOwnCourses],
        ]);
    }

    private function _loadTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/Lang', 'Course');
    }

    private function _register_policies()
    {
        Gate::policy(Course::class, CoursePolicy::class);
        Gate::policy(Season::class, SeasonPolicy::class);
        Gate::policy(Lesson::class, LessonPolicy::class);
    }

}
