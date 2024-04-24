<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserLoginRequest extends FormRequest
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
        if(request()->isMethod('post')) {
            return [
                'email' => 'required|email',
                'password' => 'required',
            ];
        } else {
            return [
                'email' => 'required|email',
                'password' => 'required',
            ];
        }
    }

    public function failedValidation(Validator $validated)
    {
        throw new HttpResponseException(response()->json([
            "success" => "false",
            "message" => "Validation Error",
            "errors" => $validated->errors(),

        ]));
    }
}
