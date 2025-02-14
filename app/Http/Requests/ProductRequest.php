<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'title' => ['required', 'string'],
            'active' => ['required', 'boolean'],
            'price' => ['required', 'numeric', 'min:1'],
            'categories' => ['required', 'array', 'min:2', 'max:10'],
            'categories.*' => ['integer', 'exists:categories,id'],
        ];
    }
}
