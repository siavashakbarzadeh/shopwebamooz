<?php

namespace Jokoli\Common\Providers;

use Illuminate\Support\ServiceProvider;

class CommonServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->_loadViews();
    }

    public function boot()
    {

    }

    private function _loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'Common');
    }
}
