<?php

namespace Jokoli\Category\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->routes(function () {
            Route::middleware(['web', 'auth', 'verified'])
                ->group(__DIR__ . '/../Routes/category_routes.php');
        });
    }
}
