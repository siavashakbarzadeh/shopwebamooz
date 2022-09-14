<?php

use Illuminate\Support\Facades\Route;
use Jokoli\Course\Http\Controllers\LessonController;

Route::get('/lessons/{course}/create', [LessonController::class, 'create'])->name('lessons.create');
Route::post('/lessons/{course}', [LessonController::class, 'store'])->name('lessons.store');
Route::delete('/lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');
Route::get('/lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('lessons.edit');
Route::patch('/lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
Route::patch('/lessons/{lesson}/accept', [LessonController::class, 'accept'])->name('lessons.accept');
Route::patch('/lessons/{lesson}/reject', [LessonController::class, 'reject'])->name('lessons.reject');
Route::patch('/lessons/{lesson}/lock', [LessonController::class, 'lock'])->name('lessons.lock');
Route::patch('/lessons/{lesson}/unlock', [LessonController::class, 'unlock'])->name('lessons.unlock');
Route::delete('/lessons', [LessonController::class, 'destroyMultiple'])->name('lessons.destroyMultiple');
Route::patch('/courses/{course}/lessons', [LessonController::class, 'acceptAll'])->name('lessons.acceptAll');
Route::patch('/lessons/accept/multiple', [LessonController::class, 'acceptMultiple'])->name('lessons.acceptMultiple');
Route::patch('/lessons/reject/multiple', [LessonController::class, 'rejectMultiple'])->name('lessons.rejectMultiple');
