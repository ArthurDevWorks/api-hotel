<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomUpdateRequest extends FormRequest
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
            'name'          => 'required|min:3',
            'description'   => 'nullable|string|max:255',
            'size'          => 'required',
            'max_adults'    => 'required|integer',
            'max_children'  => 'required|integer',
            'double_beds'   => 'required|integer',
            'single_beds'   => 'required|integer',
            'floor'         => 'required|integer',
            'type'          => 'required',
            'number'        => 'required',
            'price'         => 'required|numeric|min:0'
        ];
    }
}
