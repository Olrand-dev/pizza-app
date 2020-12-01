<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEmployee extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => ['integer'],
            'role_id' => ['required', 'numeric', 'min:1'],
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'max:100', 'unique:users', 'email:rfc,dns'],
            'password' => ['required', 'string', 'max:100', 'min:8'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:150'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'role_id.*' => 'A user role must be selected.',
        ];
    }
}
