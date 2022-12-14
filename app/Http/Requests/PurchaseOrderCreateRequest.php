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
            'invoice_number' => ['required', 'string', 'max:255', Rule::unique(PurchaseOrder::class)],
            'products' => ['required', 'array'],
            'products.*.sku' => ['required', 'string', 'max:12', Rule::exists(Product::class, 'sku')],
            'products.*.quantity' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'products.*.price' => ['required', 'numeric', 'min:0', 'max:999999999'],
        ];
    }
}
