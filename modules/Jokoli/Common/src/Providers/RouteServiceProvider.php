<?php

namespace Jokoli\Common\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->routes(function () {
            Route::middleware(['web', 'auth', 'verified'])
                ->group(__DIR__ . '/../Routes/common_routes.php');
        });
    }
}
