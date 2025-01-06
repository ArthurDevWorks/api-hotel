<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationStoreRequest;
use App\Http\Requests\ReservationUpdateRequest;
use App\Models\Guest;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Reservation::all();
        return $reservations;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReservationStoreRequest $request)
    {
        //Rollback caso der erro, + seguro
        $reservation = DB::transaction(function () use ($request) {
            return Reservation::create($request->validated());
        });
        // Retornar o endereço
        return response()->json([
            'success' => true,
            'data' => $reservation
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        return $reservation->load(['guests']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReservationUpdateRequest $request, Reservation $reservation)
    {
        $reservation = DB::transaction(function () use ($request, $reservation) {
            $reservation->update($request->validated());
            return $reservation->load('guests');
        });

        return response()->json([
            'success' => true,
            'data' => $reservation,
            'message' => 'Reserva atualizada com sucesso.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        // Retornar resposta de sucesso
        return response()->json([
            'success' => true,
            'message' => 'Reserva deletada com sucesso.'
        ], 200);
    }

    // Função para adicionar hóspedes a uma reserva
    public function addGuest(Request $request, Reservation $reservation, Guest $guest)
    {
        $request->validate([
            'type' => 'required|in:TITULAR,ACOMPANHANTE'
        ]);

        $reservation->guests()->attach($guest->id, [
            'type' => $request->type
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Hospéde adicionado realizado com sucesso.',
            'data' => $reservation->load(['guests'])
        ]);
        
    }
    // Função para registrar o check-in de um hóspede
    public function checkIn(Request $request, Reservation $reservation, Guest $guest)
    {
        // Validação dos dados
        $request->validate([
            'checkin_at' => 'required|date',
        ]);

        // Verifica se o hóspede está associado à reserva
        $pivot = $reservation->guests()->where('guest_id', $guest->id)->first();

        if ($pivot) {
            // Atualiza o valor de checkin_at na tabela pivô
            $reservation->guests()->updateExistingPivot($guest->id, [
                'checkin_at' => $request->checkin_at,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Check-in realizado com sucesso.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Hóspede não encontrado nesta reserva.',
        ], 404);
    }

    // Função para registrar o check-out de um hóspede
    public function checkOut(Request $request, Reservation $reservation, Guest $guest)
    {
        // Validação dos dados
        $request->validate([
            'checkout_at' => 'required|date|after:checkin_at',
        ]);

        // Verifica se o hóspede está associado à reserva
        $pivot = $reservation->guests()->where('guest_id', $guest->id)->first();

        if ($pivot) {
            // Atualiza o valor de checkout_at na tabela pivô
            $reservation->guests()->updateExistingPivot($guest->id, [
                'checkout_at' => $request->checkout_at,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Check-out realizado com sucesso.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Hóspede não encontrado nesta reserva.',
        ], 404);
    }
    // Função para mudar o Tipo (Titular,Acompanhante) 
    public function updateType(Request $request, Reservation $reservation, Guest $guest)
    {
        $request->validate([
            'type' => 'required|in:TITULAR,ACOMPANHANTE'
        ]);

        // Verifica se o hóspede está associado à reserva
        $pivot = $reservation->guests()->where('guest_id', $guest->id)->first();

        if ($pivot) {
            // Atualiza o valor de checkout_at na tabela pivô
            $reservation->guests()->updateExistingPivot($guest->id, [
                'type' => $request->type,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tipo atualizado com sucesso.',
                
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Hóspede não encontrado nesta reserva.',
        ], 404);
    }

}