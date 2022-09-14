<?php

namespace Jokoli\User\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class UserRouteServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    public function boot()
    {
        $this->routes(function () {

            Route::middleware('web')
                ->group(__DIR__ . '/../Routes/user_routes.php');
        });
    }
}
