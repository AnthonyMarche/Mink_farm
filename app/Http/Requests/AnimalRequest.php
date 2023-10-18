<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AnimalRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:80',
            'age' => 'required|integer|min:1',
            'description' => 'required|string',
            'price_ht' => 'required|numeric|min:0',
            'sale_status' => 'required|boolean',
            'breed_id' => 'required|exists:breeds,id',
            'image' => 'nullable|image|max:2048',
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'breed_id.required' => 'Vous devez selectionner une race.',
            'breed_id.exists' => 'La race sélectionnée n\'est pas valide.',
            'image.image' => 'Le fichier doit être une image.',
            'image.max' => 'L\'image ne doit pas dépasser 2 Mo.'
        ];
    }
}
