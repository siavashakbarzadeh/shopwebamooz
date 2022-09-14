<?php

use Illuminate\Support\Facades\Route;
use \Jokoli\Ticket\Http\Controllers\TicketController;

Route::resource('/tickets', TicketController::class);
Route::post('/tickets/{ticket}/reply', [TicketController::class,'reply'])->name('tickets.reply');
Route::patch('/tickets/{ticket}/close', [TicketController::class,'close'])->name('tickets.close');
