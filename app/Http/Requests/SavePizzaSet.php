<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SavePizzaSet extends FormRequest
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
            'base_id' => ['required', 'numeric', 'min:1'],
            'ingredients' => ['required', 'array'],
            'ingredients.*.type_id' => ['required', 'numeric', 'min:1'],
            'ingredients.*.prod_id' => ['required', 'numeric', 'min:1'],
            'ingredients.*.quantity' => ['required', 'numeric', 'min:1'],
            'image_file' => ['nullable', 'image'],
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
            'base_id.*' => 'A pizza base must be selected.',
            'ingredients.*' => 'Pizza set must have at least 1 ingredient.',
            'ingredients.*.type_id.*' => 'Ingredient type required.',
            'ingredients.*.prod_id.*' => 'Ingredient name required.',
            'ingredients.*.quantity.*' => 'Quantity required.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'ingredients' => json_decode($this->ingredients, true),
        ]);
    }
}
