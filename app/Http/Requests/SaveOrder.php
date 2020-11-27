<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveOrder extends FormRequest
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
            'customer_id' => ['required', 'numeric', 'min:1'],
            'cost' => ['required', 'numeric', 'max:99999999.99'],
            'weight' => ['required', 'numeric', 'integer'],
            'comments' => ['nullable', 'array'],
            'products' => ['nullable', 'array'],
            'products.*.id' => ['required', 'numeric', 'min:1'],
            'products.*.quantity' => ['required', 'numeric', 'min:1'],
            'pizza_sets' => ['required', 'array'],
            'pizza_sets.*.id' => ['required', 'numeric', 'min:1'],
            'pizza_sets.*.quantity' => ['required', 'numeric', 'min:1'],
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
            'customer_id.*' => 'Customer must be selected.',
            'products.*.id.*' => 'Product type required.',
            'products.*.quantity.*' => 'Quantity required.',
            'pizza_sets.*' => 'Order must have at least 1 pizza set.',
            'pizza_sets.*.id.*' => 'Pizza set type required.',
            'pizza_sets.*.quantity.*' => 'Quantity required.',
        ];
    }
}
