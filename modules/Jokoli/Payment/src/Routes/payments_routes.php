<?php

use Illuminate\Support\Facades\Route;
use Jokoli\Payment\Http\Controllers\PaymentController;

Route::get('/payments',[PaymentController::class,'index'])->name('payments.index');
Route::get('/payments/callback',[PaymentController::class,'callback'])->name('payments.callback');
Route::get('/purchases',[PaymentController::class,'purchases'])->name('purchases.index');
