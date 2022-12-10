<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Models\PurchaseOrder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseOrderCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'invoice_number' => ['required', 'string', Rule::unique(PurchaseOrder::class)],
            'products' => ['required', 'array'],
            'products.*.sku' => ['required', 'string', Rule::exists(Product::class, 'sku')],
            'products.*.quantity' => ['required', 'integer', 'max:4294967295'],
            'products.*.price' => ['required', 'string', 'max_digits:18'],
        ];
    }
}
