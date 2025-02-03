<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StateStoreRequest;
use App\Http\Requests\StateUpdateRequest;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $states = State::paginate();
        
        return $states;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StateStoreRequest $request)
    {
        $state = DB::transaction(function() use ($request){
         $state = State::create($request->validated());
        }); 

        return $state;
    }

    /**
     * Display the specified resource.
     */
    public function show(State $state)
    {
        return $state->load();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StateUpdateRequest $request, State $state)
    {
        $state->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => $state,
            'message' => 'State atualizado com sucesso.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(State $state)
    {
        $state->delete();

        // Retornar resposta de sucesso
        return response()->json([
            'success' => true,
            'message' => 'State deletado com sucesso.'
        ], 200);
    }
}
