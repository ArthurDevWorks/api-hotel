<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Obtém o endereço da rota (Route Model Binding)
        $address = $this->route('address');

        // Verifica se o endereço pertence ao Guest associado ao usuário logado
        return $address && $address->guest && $address->guest->user_id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'zipcode' => 'required',
            'city' => 'required',
            'state' => 'required',
            'street'=> 'required',
            'district'=> 'required',
            'number'=> 'required',
            'complement'=> 'required'
        ];
    }
}
