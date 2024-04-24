<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool 
    {
        // return false;
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
                'name' => 'required|string|max:258', 
                'email' => 'required|email|unique:users', 
                'password' => 'required', 
                'c_password' => 'required|same:password'
            ];
        } else {
            return [
                'name' => 'required|string|max:258', 
                'email' => 'required|email|unique:users', 
                'password' => 'required', 
                'c_password' => 'required|same:password'
            ];
        }
    }

    public function messages()
    {
        if(request()->isMethod('post')) {
            return [
                'name.required' => 'Name is required!',
                'email.required' => 'Email is required!',
                'email.email' => 'Please Enter valide Email!',
                'email.unique:users' => 'This Email is currently use on this system!',
                'password.required' => 'Password is required!',
                'c_password.required' => 'Please Reenter Your Password!',
                'c_password.same:password' => 'Please Enter same password !'
            ];
        } else {
            return [
                'name.required' => 'Name is required!',
                'email.required' => 'Email is required!',
                'email.email' => 'Please Enter valide Email!',
                'email.unique:users' => 'This Email is currently use on this system!',
                'password.required' => 'Password is required!',
                'c_password.required' => 'Please Reenter Your Password!',
                'c_password.same:password' => 'Please Enter same password !'
            ];   
        }
    }
}
