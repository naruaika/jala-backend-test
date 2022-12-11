<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Models\SaleOrder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaleOrderCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'invoice_number' => ['sometimes', 'required', 'string', 'max:255', Rule::unique(SaleOrder::class)],
            'customer_name' => ['required', 'string', 'max:255'],
            'products' => ['required', 'array'],
            'products.*.sku' => ['required', 'string', 'max:12', Rule::exists(Product::class, 'sku')],
            'products.*.quantity' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'products.*.price' => ['required', 'numeric', 'min:0', 'max:999999999'],
        ];
    }
}
