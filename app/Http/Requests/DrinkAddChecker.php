<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class DrinkAddChecker extends FormRequest
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
    public function rules(): array {
        
        return [
            "drink" => "required",
            "amount" => "required",
            "type" => "required",
            "quantity" => "required"
        ];
    }

    public function messages() {

        return [
            "drink.required" => "Név elvárt",
            "amount.required" => "Mennyiség elvárt",
            "type.required" => "Típus elvárt",
            "quantity.required" => "Kiszerelés elvárt",
        ];
    }

    public function failedValidation( Validator $validator ) {

        throw new HttpResponseException( response()->json([
            "success" => false,
            "message" => "Adatbeviteli hiba",
            "data" => $validator->errors()
        ]));
    }
}
