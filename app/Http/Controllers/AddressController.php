<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressStoreRequest;
use App\Http\Requests\AddressUpdateRequest;
use App\Models\Address;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Guest $guest)
    {
        //Nao recomendado, se houver muitos cadastros pode matar a aplicaçao.
        //$address = Address::all();

        //Inicia uma query
        $addresses = $guest->addresses();

        //Filtro por zipcode
        if($request->has('zipcode')){
            $addresses->where('zipcode','like'. $request->get('zipcode'));
        }

        //Filtro por city
        if($request->has('city')){
            $addresses->where('zipcode','like'. $request->get('city'));
        }

        return $addresses->paginate();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddressStoreRequest $request, Guest $guest)
    {
        //Inicia a transaçao
        $address = DB::transaction(function () use ($request, $guest) {
            return $guest->addresses->create($request->validated());
        });
        return $address;
    }

    /**
     * Display the specified resource.
     */
    //Passa o id do endereco por parametro para ser buscado
    public function show(Guest $guest, Address $address)
    {
        if($address->guest_id != $guest->id){
            return response()->json(['error' => 'Endereco nao encontrado para este hospede.']);
        }
        return $address;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AddressUpdateRequest $request, Guest $guest, Address $address)
    {
        if($address->guest_id != $guest->id){
            return response()->json(['error' => 'Voce nao tem permissao para atualizar este endereco.']);
        }
       //Inicia a transaçao
        $address = DB::transaction(function () use ($request, $address) {
            $address->update($request->validated());
            return $address->load('guest');
        });
        return $address;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guest $guest, Address $address)
    {
        if($address->guest_id != $guest->id){
            return response()->json(['error' => 'Voce nao tem permissao para deletar este usuarios']);
        }

        // Exclui o endereço
        $address->delete();

        return response()->noContent();
    }
}
