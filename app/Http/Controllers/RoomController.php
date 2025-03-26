<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\RoomStoreRequest;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Room $room)
    {
        $room = Room::paginate(20);

        return $room;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(RoomStoreRequest $request)
    {
        $room = DB::transaction(function() use ($request){
            $room = Room::create($request->validated());

            return $room;
        });

        return response()->json([
            'sucess' => true,
            'data'   => $room
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
