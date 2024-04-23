<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BreakTimeController;
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

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('start', [AttendanceController::class, 'start'])->name('attendance.start');
    Route::patch('end', [AttendanceController::class, 'end'])->name('attendance.end');
    Route::post('break-start', [BreakTimeController::class, 'start'])->name('break.start');
    Route::patch('break-end', [BreakTimeController::class, 'end'])->name('break.end');

    Route::get('attendance/{date?}', [AttendanceController::class ,'date'])->name('attendance.date.show');

    Route::prefix('users')->group(function() {
        Route::get('/', [UserController::class, 'users'])->name('users.show');
        Route::get('{user_id}', [UserController::class, 'user'])->name('user.attendance.show');
    });
});