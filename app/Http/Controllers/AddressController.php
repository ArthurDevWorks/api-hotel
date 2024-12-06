<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressStoreRequest;
use App\Models\Address;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //Nao recomendado, se houver muitos cadastros pode matar a aplicaçao.
        //$address = Address::all();

        //Inicia uma query
        $addresses = Address::query();

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
    public function store(AddressStoreRequest $request)
    {
        $address = DB::transaction(function () use ($request) {


            $guest = Guest::find($request->guest_id);

            // Verifica se o guest foi encontrado
            if (!$guest) {
                throw new \Exception('Guest not found'); // Caso o guest não exista, lance uma exceção
            }

            // Criação do endereço associado ao guest
            $address = $guest->addresses()->create($request->validated());

            // Retorna o guest com o endereço carregado
            return $address->load('guest');
        });

        // Retorna o endereço criado
        return $address;
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
