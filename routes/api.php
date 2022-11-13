<?php

use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\TimeSlotController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/users', UserController::class);
Route::get('/locations', LocationController::class);

Route::get('/timeslots/{location}/{date}', [TimeSlotController::class, 'index']);
Route::post('/timeslots', [TimeSlotController::class, 'toggle']);
