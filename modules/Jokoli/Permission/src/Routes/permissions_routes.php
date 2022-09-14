<?php

use Illuminate\Support\Facades\Route;
use Jokoli\Permission\Http\Controllers\PermissionController;

Route::resource('/permissions', PermissionController::class);
