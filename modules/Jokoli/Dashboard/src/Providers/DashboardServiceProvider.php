<?php

namespace Jokoli\Dashboard\Providers;

use Illuminate\Support\ServiceProvider;

class DashboardServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->_loadViews();
        $this->_loadConfig();
    }

    public function boot()
    {
        $this->_sidebar();
    }

    private function _loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'Dashboard');
    }

    public function _loadConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/sidebar.php', 'sidebar');
    }

    private function _sidebar()
    {
        config()->set('sidebar.items.dashboard', [
            'name' => 'پیشخوان',
            'icon' => 'i-dashboard',
            'route' => 'home',
            'routes' => 'home'
        ]);
    }
}
