<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Jokoli\User\Http\Controllers\Auth\ForgotPasswordController;
use Jokoli\User\Http\Controllers\Auth\LoginController;
use Jokoli\User\Http\Controllers\Auth\RegisterController;
use Jokoli\User\Http\Controllers\Auth\ResetPasswordController;
use Jokoli\User\Http\Controllers\Auth\VerificationController;
use Jokoli\User\Http\Controllers\TutorController;
use Jokoli\User\Http\Controllers\UserController;

Route::resource('users', UserController::class);
Route::patch('users/{user}/manual-verify',[UserController::class,'manualVerify'])->name('users.manual-verify');

Route::middleware(['auth','verified'])->group(function (Router $router){
    $router->post('users/photo',[UserController::class,'photo'])->name('users.photo');
    $router->get('profile',[UserController::class,'profile'])->name('profile');
    $router->patch('profile',[UserController::class,'updateProfile']);
    $router->get('users/{users}/info',[UserController::class,'info'])->name('users.info');
});

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::get('password/reset', [ForgotPasswordController::class, 'showVerifyCodeRequestFrom'])->name('password.request');
Route::get('password/reset/send', [ForgotPasswordController::class, 'sendVerifyCodeEmail'])->name('password.sendVerifyCodeEmail');
Route::post('password/reset/check-verify-code', [ForgotPasswordController::class, 'checkVerifyCode'])
    ->middleware('throttle:5,1')->name('password.check-verify-code');
Route::get('password/change', [ResetPasswordController::class, 'showResetForm'])
    ->middleware('auth')->name('password.showResetForm');
Route::post('password/change', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::post('email/verify', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

