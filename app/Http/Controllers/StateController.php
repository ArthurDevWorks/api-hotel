<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StateStoreRequest;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $states = State::paginate(10);
        
        return $states;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StateStoreRequest $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
