<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveProduct extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'type_id' => ['required', 'numeric'],
            'cost' => ['required', 'numeric', 'max:99999999.99'],
            'weight' => ['required', 'numeric', 'integer'],
            'description' => ['nullable', 'string'],
            'image_file' => ['nullable', 'image'],
            'image_changed' => ['string'],
        ];
    }
}
