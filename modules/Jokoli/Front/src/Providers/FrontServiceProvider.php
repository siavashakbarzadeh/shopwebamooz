<?php

namespace Jokoli\Front\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;
use Jokoli\Category\Repository\CategoryRepository;

class FrontServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->_loadMigrations();
        $this->_loadViews();
    }

    public function boot()
    {
        $this->_getCategories();
    }

    public function _getCategories()
    {
        view()->composer('Front::layouts.categories', function (View $view) {
            $view->with('categories', resolve(CategoryRepository::class)->getCategories());
        });
    }

    private function _loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations/');
    }

    private function _loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'Front');
    }
}
