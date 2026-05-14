<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SpaceController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/reservations', [ReservationController::class, 'store']);

Route::get('/spaces/{space}', [SpaceController::class, 'show']);
Route::get('/spaces/{space}/availability', [SpaceController::class, 'availability']);