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
        //Inicia a transaçao
        $address = DB::transaction(function () use ($request) {

            //Cria o endereço
            $address = Address::create($request->validated());

            //Associa o endereco ao guest
            $address->guest()->create($request->validated());

            //Carrega o relacionamento
            return $address->load('guest');
        });
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
