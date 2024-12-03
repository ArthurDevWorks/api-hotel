<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\GuestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('guests',GuestController::class)
    ->middleware(['auth:sanctum']);

Route::apiResource('addresses', AddressController::class)
    ->middleware(['auth:sanctum']);