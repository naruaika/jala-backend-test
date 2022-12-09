<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'sku' => ['required', 'string', 'max:12', Rule::unique(Product::class)],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string', 'max_digits:18'],
        ];
    }
}
