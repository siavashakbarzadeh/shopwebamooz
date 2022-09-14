<?php

use Illuminate\Support\Facades\Route;
use Jokoli\Dashboard\Http\Controllers\DashboardController;

Route::get('/home', [DashboardController::class, 'home'])->name('home');
