<?php

use Jokoli\Course\Http\Controllers\CourseController;
use \Illuminate\Support\Facades\Route;
use Jokoli\Course\Http\Controllers\SeasonController;

Route::post('/seasons/{course}', [SeasonController::class, 'store'])->name('seasons.store');
Route::get('/seasons/{season}', [SeasonController::class, 'edit'])->name('seasons.edit');
Route::patch('/seasons/{season}', [SeasonController::class, 'update'])->name('seasons.update');
Route::delete('/seasons/{season}', [SeasonController::class, 'destroy'])->name('seasons.destroy');
Route::patch('/seasons/{season}/accept', [SeasonController::class, 'accept'])->name('seasons.accept');
Route::patch('/seasons/{season}/reject', [SeasonController::class, 'reject'])->name('seasons.reject');
Route::patch('/seasons/{season}/lock', [SeasonController::class, 'lock'])->name('seasons.lock');
Route::patch('/seasons/{season}/unlock', [SeasonController::class, 'unlock'])->name('seasons.unlock');
