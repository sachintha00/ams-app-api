<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSubUsersRequest extends FormRequest
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
                'user_name' => 'required|string|max:258', 
                'email' => 'required|email|unique:users',
                'contact_no' => 'required', 
                'roles' => 'required'
            ];
        } else {
            return [
                'user_name' => 'required|string|max:258', 
                'email' => 'required|email|unique:users',
                'contact_no' => 'required',  
                'roles' => 'required'
            ];
        }
    }
}
