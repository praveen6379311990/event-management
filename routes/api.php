<?php

use App\Http\Controllers\api\AttendeeController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login',[AuthController::class, 'login']);
Route::post('/logout',[AuthController::class, 'logout'])->middleware('auth:sanctum');

// Public routes (no auth)
Route::apiResource('events', EventController::class)->only(['index', 'show']);

// Protected routes (with auth)
Route::apiResource('events', EventController::class)
    ->only(['store', 'update', 'destroy'])
    ->middleware(['auth:sanctum','throttle:api']);

Route::apiResource('events.attendees',AttendeeController::class)
->scoped(['attendees' =>'events'])
->only(['index','show','update']);

Route::apiResource('events.attendees',AttendeeController::class)
->scoped(['attendees' =>'events'])->except('update')->middleware('auth:sanctum')
->only(['store','destroy']);
