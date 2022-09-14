<?php

use Illuminate\Support\Facades\Route;
use Jokoli\Payment\Http\Controllers\SettlementController;

Route::resource('/settlements', SettlementController::class);
