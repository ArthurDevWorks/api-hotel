<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class GuestStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email'=>['required','email',Rule::unique('users')->ignore(auth()->user()->id)],
            'password' => ['required', 'min:8', 'max:25', 'confirmed', Password::min(8)->letters()->numbers()->uncompromised()],
            'name' => 'required|string|min:3|max:255',
            'birthdate'=> 'required|date|before:now',
            'is_foreigner'=>'required|boolean',
            'cpf'=>'required_if:is_foreigner,0|max:11',
            'rg'=>'required_if:is_foreigner,0|max:15',
            'passport'=>'required_if:is_foreigner,1|string|min:3|max:255'
        ];
    }
}
