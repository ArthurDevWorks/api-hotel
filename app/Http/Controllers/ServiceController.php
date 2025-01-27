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
}
