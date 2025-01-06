<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhoneStoreRequest;
use App\Http\Requests\PhoneUpdateRequest;
use App\Models\Guest;
use App\Models\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PhoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Guest $guest)  // http://localhost:8000/api/guests/2/phones
    {
        // Busca os telefones associado ao guest(pelo id)
        $phones = $guest->phones()->get();
        return $phones;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PhoneStoreRequest $request,Guest $guest)
    //POST: http://localhost:8000/api/guests/2/phones
    {
        // Criação do telefone associado ao Guest
        $phone = $guest->phones()->create($request->validated());

        // Retorno do Guest e do Phone criado
        return response()->json([
            'success' => true,
            'data' => [
                'guest' => $guest,
                'phone' => $phone, //apenas o telefone criado
            ],
            'message' => 'Telefone cadastrado com sucesso.'
        ], 201);   
    }

    /**
     * Display the specified resource.
     */
    public function show(Guest $guest, Phone $phone) // http://localhost:8000/api/guests/2/phones/1
    {
        // Verificar se o telefone pertence ao hóspede
        if ($phone->guest_id !== $guest->id) {
            return response()->json([
                'success' => false,
                'message' => 'O telefone não pertence ao hospede informado.'
            ], 404);
        }

        // Retornar o telefone
        return response()->json([
            'success' => true,
            'data' => $phone
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PhoneUpdateRequest $request, Guest $guest, Phone $phone)
    {
        // Verificar se o telefone pertence ao hóspede
        if ($phone->guest_id !== $guest->id) {
            return response()->json([
                'success' => false,
                'message' => 'O telefone não pertence ao hóspede informado.'
            ], 404);
        }

        // Atualizar os dados do telefone
        $phone->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => $phone,
            'message' => 'Telefone atualizado com sucesso.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guest $guest, Phone $phone)
    {
        // Verificar se o telefone pertence ao hóspede
        if ($phone->guest_id !== $guest->id) {
            return response()->json([
                'success' => false,
                'message' => 'O telefone não pertence ao hóspede informado.'
            ], 404);
        }

        // Deletar o telefone
        $phone->delete();

        // Retornar resposta de sucesso
        return response()->json([
            'success' => true,
            'message' => 'Telefone deletado com sucesso.'
        ], 200);
    }
}
