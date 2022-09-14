<?php

namespace Jokoli\Permission\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\Permission\Enums\Roles;
use Jokoli\Permission\Models\Permission;
use Jokoli\Permission\Policies\PermissionPolicy;

class PermissionServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->_loadMigrations();
        $this->_loadViews();
        $this->_loadTranslations();
        $this->_loadTranslationJson();
    }

    public function boot()
    {
        $this->_sidebar();
        $this->_supper_admin();
        $this->_register_policies();
    }

    private function _loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations/');
    }

    private function _loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'Permission');
    }

    private function _sidebar()
    {
        config()->set('sidebar.items.permissions', [
            'name' => 'نقش‌های کاربری',
            'icon' => 'i-permissions',
            'route' => 'permissions.index',
            'routes' => 'permissions.*',
            'permissions' => Permissions::ManagePermissions,
        ]);
    }

    private function _supper_admin()
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole(Roles::SupperAdmin) ? true : null;
        });
    }

    private function _loadTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/Lang', 'Permission');
    }

    private function _loadTranslationJson()
    {
        $this->loadJsonTranslationsFrom(__DIR__ . '/../Resources/Lang/');
    }

    private function _register_policies()
    {
        Gate::policy(Permission::class,PermissionPolicy::class);
    }
}
