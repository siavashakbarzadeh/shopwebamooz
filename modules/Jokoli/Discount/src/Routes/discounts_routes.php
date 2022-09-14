<?php

use Illuminate\Support\Facades\Route;
use Jokoli\Discount\Http\Controllers\DiscountController;

Route::resource('/discounts', DiscountController::class);
Route::post('/discounts/{course}/check',[DiscountController::class,'check'])
    ->middleware('throttle:6,1')->name('discounts.check');
