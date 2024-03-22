<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;

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

Route::middleware('auth')->group(function () {
    Route::controller(AttendanceController::class)->group(function() {
        Route::get('/', 'index');
        Route::post('/start', 'start');
        Route::put('/end', 'end');
        Route::post('/break-start', 'breakStart');
        Route::put('/break-end', 'breakEnd');
        Route::get('/attendance', 'date');
    });
});
