<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ServiceStoreRequest;
use App\Http\Requests\ServiceUpdateRequest;
use App\Models\Reservation;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::all();
        return $services;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServiceStoreRequest $request)
    {
        $service = Service::create($request->validated());

        return response()->json([
            'success' => true,
            'data' => $service,
            'message' => 'Serviço cadastrado com sucesso.'
        ], 201); 
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return $service;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServiceUpdateRequest $request, Service $service)
    {
         $service = DB::transaction(function () use ($request, $service) {
            $service->update($request->validated());
            return $service;
        });

        return response()->json([
            'success' => true,
            'data' => $service,
            'message' => 'Serviço atualizado com sucesso.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Serviço deletado com sucesso.'
        ], 200);
    }

    // Função para adicionar serviço a uma reserva
    public function addService(Request $request, Service $service, Reservation $reservation)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric'
        ]);

        //Verifica se ja existe algunm serviço atrelado a reserva para evitar duplicidade
        if (!$service->reservations()->where('reservation_id', $reservation->id)->exists()) {
            $service->reservations()->attach($reservation->id, [
                'name' => $request->name,
                'price' => $request->price
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Serviço adicionado com sucesso.',
            'data' => $service->load(['reservations:id,nameeme '])
        ]);
    }

    //Funcao para editar o preço do serviço atrelado a reserva
    public function updateService(Request $request, Service $service, Reservation $reservation)
    {
        $request->validate([
            'price' => 'required|numeric'
        ]);

         // Verifica se a reserva já está associada ao serviço na tabela pivot
         $pivot = $service->reservations()->where('reservation_id', $reservation->id)->first();

        if ($pivot) {
            $reservation->reservations()->updateExistingPivot($reservation->id, [
                'price' => $request->price
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Preço atualizado com sucesso.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Não há nenhum serviço nesta reserva.',
        ], 404);
    }
}
