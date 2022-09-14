<?php

namespace Jokoli\User\Providers;

use App\Http\Kernel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Jokoli\Media\Database\Factories\MediaFactory;
use Jokoli\Media\Models\Media;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\User\Http\Middleware\StoreUserIp;
use Jokoli\User\Models\User;
use Jokoli\User\Policies\UserPolicy;
use Jokoli\User\View\Components\UserPhoto;

class UserServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->_loadMigrations();
        $this->_loadViews();
        $this->_setConfigAuthModel();
        $this->_loadTranslations();
    }

    public function boot()
    {
        $this->_loadFactories();
        $this->_loadComponents();
        $this->_sidebar();
        $this->_register_policies();
        $this->app->booted(function () {
            $this->app['router']->pushMiddlewareToGroup('web', StoreUserIp::class);
        });
    }

    private function _loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations/');
    }

    private function _loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views/', 'User');
    }

    private function _loadComponents()
    {
        $this->loadViewComponentsAs('user', [UserPhoto::class]);
    }

    private function _loadFactories()
    {
        Factory::guessFactoryNamesUsing(function ($model) {
            return $model::Factory;
        });
    }

    private function _sidebar()
    {
        config()->set('sidebar.items.users', [
            'name' => 'کاربران',
            'icon' => 'i-users',
            'route' => 'users.index',
            'routes' => 'users.*',
            'permissions' => Permissions::ManageUsers,
        ]);
        config()->set('sidebar.items.profile', [
            'name' => 'اطلاعات کاربری',
            'icon' => 'i-user__inforamtion',
            'route' => 'profile',
            'routes' => 'profile',
        ]);
    }

    private function _setConfigAuthModel()
    {
        Config::set('auth.providers.users.model', User::class);
    }

    private function _loadTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/Lang', 'User');
    }

    private function _register_policies()
    {
        Gate::policy(User::class, UserPolicy::class);
    }
}
