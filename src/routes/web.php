<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserController;

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
    Route::prefix('/')->group(function() {
        Route::get('', [AttendanceController::class, 'index']);
        Route::post('start', [AttendanceController::class, 'start']);
        Route::put('end', [AttendanceController::class, 'end']);
        Route::post('break-start', [AttendanceController::class, 'breakStart']);
        Route::put('break-end', [AttendanceController::class, 'breakEnd']);
    });

    Route::get('attendance/{date?}', [AttendanceController::class ,'date']);

    Route::prefix('users')->group(function() {
        Route::get('', [UserController::class, 'users']);
        Route::get('/search', [UserController::class, 'search']);
        Route::get('{user}', [AttendanceController::class, 'user']);
    });
});