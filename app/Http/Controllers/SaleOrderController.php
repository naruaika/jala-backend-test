<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaleOrderCreateRequest;
use App\Models\Product;
use App\Models\SaleOrder;
use App\Models\SaleOrderDetail;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class SaleOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        if (! Gate::allows('viewAny', SaleOrder::class)) {
            return redirect(RouteServiceProvider::HOME);
        }

        return Inertia::render('SaleOrders/Index', [
            'products' => Product::query()
                ->select('sku', 'name', 'price', 'stock')
                ->where('stock', '>', 0)
                ->orderBy('sku')
                ->get(),
            'saleOrders' => SaleOrder::query()
                ->select('customer_name', 'price', 'status', 'created_at')
                ->where('created_by', auth()->user()->id)
                ->latest()
                ->get(),
            'permissions' => [
                'create' => Gate::allows('create', SaleOrder::class),
            ],
        ]);
    }

    /**
     * Create a new resource.
     *
     * @param  \App\Http\Requests\SaleOrderCreateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SaleOrderCreateRequest $request)
    {
        if (! Gate::allows('create', SaleOrder::class)) {
            abort(403);
        }

        // Get the validated input
        $input = $request->validated();

        // FIXME: optimise these queries to using batch execution
        DB::transaction(function () use ($input) {
            // Prepare a transaction
            $skus = collect($input['products'])->pluck('sku')->toArray();
            $products = Product::whereIn('sku', $skus)->lockForUpdate()->get();

            $totalPrice = 0;

            // Re-validate the request after the related product record is locked
            $rules = [];
            foreach ($input['products'] as $index => $product) {
                $product = $products->where('sku', $product['sku'])->first();
                $rules['products.'.$index.'.quantity'] = 'integer|max:'.$product->stock;
            }
            Validator::validate($input, $rules);

            // Create a new sale order
            $saleOrder = SaleOrder::create([
                'invoice_number' => null,
                'customer_name' => $input['customer_name'],
                'price' => $totalPrice,
                'status' => 'pending',
                'created_by' => auth()->user()->id,
            ]);

            foreach ($input['products'] as $inputedProduct) {
                $referencedProduct = $products->where('sku', $inputedProduct['sku'])->first();

                // Calculate the subtotal of the sale order detail
                $subtotal = $inputedProduct['quantity'] * $inputedProduct['price'];

                // Update the total transaction cost of the sale order
                $totalPrice += $subtotal;

                // Create a new sale order detail
                SaleOrderDetail::create([
                    'sale_order_id' => $saleOrder->id,
                    'product_id' => $referencedProduct->id,
                    'quantity' => $inputedProduct['quantity'],
                    'price' => $inputedProduct['price'],
                    'subtotal' => $subtotal,
                ]);

                // Update the stock of the related product
                $stock = $referencedProduct->stock - $inputedProduct['quantity'];
                $referencedProduct->update(['stock' => $stock]);
            }

            // Store the total transaction cost of the sale order
            $saleOrder->update(['price' => $totalPrice]);
        });

        return Redirect::route('sale-orders.index');
    }
}
