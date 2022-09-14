<?php

namespace Jokoli\Discount\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Jokoli\Discount\Models\Discount;
use Jokoli\Discount\Policies\DiscountPolicy;
use Jokoli\Permission\Enums\Permissions;

class DiscountServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'Discount');
    }

    private function _sidebar()
    {
        config()->set('sidebar.items.discounts', [
            'name' => 'تخفیف ها',
            'icon' => 'i-discounts',
            'route' => 'discounts.index',
            'routes' => 'discounts.*',
            'permissions' => Permissions::ManageDiscounts,
        ]);
    }

    private function _register_policies()
    {
        Gate::policy(Discount::class, DiscountPolicy::class);
    }
}
