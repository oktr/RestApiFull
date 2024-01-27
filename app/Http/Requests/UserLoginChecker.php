<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Contracts\Validation\Validator;

class UserLoginChecker extends FormRequest
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
            "name" => "required",
            "password" => "required"
        ];
    }

    public function messages() {

        return [
            "name.required" => "Név kötelező",
            "password.required" => "Jelszó kötelező"
        ];
    }

    public function failedValidation( Validator $validator ) {

        throw new HttpResponseException( response()->json([
            "success" => false,
            "message" => "Adatbeviteli hiba",
            "data" => $validator->errors()
        ]));
    }

    // public function tooManyLoginAttempt() {

    //     throw new ThrottleRequestException( response()->json([
    //         "success" => false,
    //         "message" => "Túl sok próbálkozás"
    //     ]));
    // }
}