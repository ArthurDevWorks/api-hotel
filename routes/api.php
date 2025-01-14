<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ServiceController;
use App\Models\Service;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('guests',GuestController::class)
    ->middleware(['auth:sanctum']);

Route::apiResource('guests.addresses', AddressController::class)
    ->middleware(['auth:sanctum']);

Route::apiResource('reservations', ReservationController::class);

//Foram nessarias estas rotas pois elas nao sao os metodos padroes do crud. ex:index,store...
Route::post('reservations/{reservation}/guests/{guest}/add-guest', [ReservationController::class, 'addGuest']);

// Rota para o check-in de um hóspede
Route::post('reservations/{reservation}/guests/{guest}/checkin', [ReservationController::class, 'checkIn']);
    
// Rota para o check-out de um hóspede
Route::post('reservations/{reservation}/guests/{guest}/checkout', [ReservationController::class, 'checkOut']);

// Rota para atualizar o tipo de um hóspede
Route::post('reservations/{reservation}/guests/{guest}/updateType', [ReservationController::class, 'updateType']);

Route::apiResource('services',ServiceController::class);

//Rota para o serviço de uma reserva
Route::post('services/{service}/{reservations}/{reservation}/addService', [ServiceController::class, 'addService']);

//Rota para atualizar serviço de uma reserva
Route::post('services/{service}/{reservations}/{reservation}/updateService',
[ServiceController::class, 'updateService']);