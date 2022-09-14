<?php

namespace Jokoli\Category\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Jokoli\Category\Models\Category;
use Jokoli\Category\Policies\CategoryPolicy;
use Jokoli\Permission\Enums\Permissions;

class CategoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->_loadMigrations();
        $this->_loadViews();
    }

    public function boot()
    {
        $this->_sidebar();
        $this->_register_policies();
    }

    private function _loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations/');
    }

    private function _loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'Category');
    }

    private function _sidebar()
    {
        config()->set('sidebar.items.categories', [
            'name' => 'دسته بندی ها',
            'icon' => 'i-categories',
            'route' => 'categories.index',
            'routes' => 'categories.*',
            'permissions' => Permissions::ManageCategories,
        ]);
    }

    private function _register_policies()
    {
        Gate::policy(Category::class, CategoryPolicy::class);
    }
}
