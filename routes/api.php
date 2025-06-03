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

Route::apiResource('events',EventController::class)
->middleware('auth:sanctum')->except(['index','show']);

Route::apiResource('events.attendees',AttendeeController::class)->scoped(['attendees' =>'events'])->except('update');
