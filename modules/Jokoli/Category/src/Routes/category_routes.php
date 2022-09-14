<?php

use Illuminate\Support\Facades\Route;
use Jokoli\Category\Http\Controllers\CategoryController;

Route::resource('/categories', CategoryController::class);
