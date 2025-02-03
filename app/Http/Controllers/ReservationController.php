<?php
namespace App\Http\Controllers;
use App\Models\Guest;
use App\Models\Service;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ReservationStoreRequest;
use App\Http\Requests\ReservationUpdateRequest;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Reservation::all();
        return $reservations::with('guests','state');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(ReservationStoreRequest $request)
    {
        //Rollback caso der erro, + seguro
        $reservation = DB::transaction(function () use ($request) {
            $reservation = Reservation::create($request->validated());

            if($request->has('guest_ids')){
                $reservation->guests()->attach($request->guest_ids);
            }

            return $reservation;
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
        return $reservation->load(['guests','state']);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(ReservationUpdateRequest $request, Reservation $reservation)
    {
        $reservation = DB::transaction(function () use ($request, $reservation) {
            $reservation->update($request->validated());

            if($request->has('guest_ids'))
            {
                $reservation->guests()->sync($request->guest_ids);
            }

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
        DB::transaction(function () use ($reservation) {
            $reservation->guests()->detach(); // Remover todos os hóspedes vinculados
            $reservation->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Reserva deletada com sucesso.'
        ], 200);
    }

    /**
    * Add service to reservation
    */
    public function addService(Reservation $reservation, Service $service)
    {   
        $reservation->services()->attach($service->id);

        return response()->json([
            'sucess' => true,
            'message' => 'Serviço adicionado à reserva com sucesso .',
            'data' => $reservation->load('services')
        ]);
    }
}