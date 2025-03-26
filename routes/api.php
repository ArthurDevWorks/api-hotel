<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ReservationController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('guests',GuestController::class)
    ->middleware(['auth:sanctum']);

Route::apiResource('guests.addresses', AddressController::class)
    ->middleware(['auth:sanctum']);

Route::apiResource('reservations', ReservationController::class);

//Rota para atrelar reservas aos serviços
Route::post('reservations/{reservation}/services/{service}/add-service', [ReservationController::class, 'addService']);

//Rota para atrelar reservas aos hospedes
Route::post('reservations/{reservation}/guests/{guest}/add-guest', [ReservationController::class, 'addGuest']);

// Rota para o check-in de um hóspede
Route::post('reservations/{reservation}/guests/{guest}/checkin', [ReservationController::class, 'checkIn']);
    
// Rota para o check-out de um hóspede
Route::post('reservations/{reservation}/guests/{guest}/checkout', [ReservationController::class, 'checkOut']);

// Rota para atualizar o tipo de um hóspede
Route::post('reservations/{reservation}/guests/{guest}/updateType', [ReservationController::class, 'updateType']);

//Rota de servicos
Route::apiResource('services',ServiceController::class);

//Rota de quartos
Route::apiResource('rooms',RoomController::class)
    ->middleware(['auth:sanctum']);