<?php

use Jokoli\Payment\Events\PaymentWasSuccessful;
use Faker\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Jokoli\Course\Enums\CourseConfirmationStatus;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    \Illuminate\Support\Facades\Storage::disk('local')->put('slaam.jpg', file_get_contents('https://www.bicoo.ir/img/courses/2021/300_6046436230a3d.jpg'));
    return view('index');
});*/


Route::get('/test', function () {
    event(new PaymentWasSuccessful(Jokoli\Payment\Models\Payment::query()->latest()->first()));
});
