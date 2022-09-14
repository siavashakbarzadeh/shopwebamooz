<?php

namespace Jokoli\Course\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->routes(function () {
            Route::middleware(['web', 'auth', 'verified'])
                ->group(__DIR__ . '/../Routes/courses_routes.php');
            Route::middleware(['web', 'auth', 'verified'])
                ->group(__DIR__ . '/../Routes/seasons_routes.php');
            Route::middleware(['web', 'auth', 'verified'])
                ->group(__DIR__ . '/../Routes/lessons_routes.php');
        });
    }
}
