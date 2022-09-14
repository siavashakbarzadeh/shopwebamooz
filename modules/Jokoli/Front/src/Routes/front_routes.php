<?php

use Illuminate\Support\Facades\Route;
use Jokoli\Front\Http\Controllers\FrontController;

Route::get('/',[FrontController::class,'index'])->name('index');
Route::get('/c-{course}/{slug?}',[FrontController::class,'singleCourse'])
    ->where('course', '[0-9]+')->name('single-course');
Route::get('/tutors/{username}',[FrontController::class,'tutor'])->name('tutor');
