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

            //Pega o guest logado
            $guest = auth()->user()->guest;

            //Verifica se encontrou
            if(!$guest){
                throw new \Exception('Guest not found');
            }

            //cria um endereco associado ao guest logado
            $address = $guest->addresses()->create($request->validated());

            //Carrega o relacionamento
            return $address->load('guest');
        });
        return $address;
    }

    /**
     * Display the specified resource.
     */
    //Passa o id do endereco por parametro para ser buscado
    public function show(Address $address)
    {
        return $address->load(['guest']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AddressUpdateRequest $request, Address $address)
    {
       //Inicia a transaçao
        $address = DB::transaction(function () use ($request, $address) {

            $address->update($request->validated());

            //Carrega o relacionamento
            return $address->load('guest');
        });
        return $address;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = auth()->user();

        // Busca o endereço pelo ID e verifica se pertence ao guest do usuário logado
        $address = Address::where('id', $id)->whereHas('guest', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->first();

        // Se o endereço não for encontrado ou não pertencer ao usuário logado, retorna 403
        if (!$address) {
            return response()->json(['error' => 'Você não tem permissão para excluir este endereço.'], 403);
        }

        // Exclui o endereço
        $address->delete();

        return response()->noContent(); // 204 No Content em caso de sucesso
    }
}
