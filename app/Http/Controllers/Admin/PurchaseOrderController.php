<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseOrderCreateRequest;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        if (! Gate::allows('viewAny', PurchaseOrder::class)) {
            return redirect(RouteServiceProvider::HOME);
        }

        return Inertia::render('Admin/PurchaseOrders/Index', [
            'products' => Product::query()
                ->select('sku', 'name', 'price', 'stock')
                ->orderBy('sku')
                ->get(),
            'purchaseOrders' => PurchaseOrder::query()
                ->select('invoice_number', 'price', 'created_at')
                ->latest()
                ->get(),
            'permissions' => [
                'create' => Gate::allows('create', PurchaseOrder::class),
            ],
        ]);
    }

    /**
     * Create a new resource.
     *
     * @param  \App\Http\Requests\PurchaseOrderCreateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PurchaseOrderCreateRequest $request)
    {
        if (! Gate::allows('create', PurchaseOrder::class)) {
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

            // Create a new purchase order
            $purchaseOrder = PurchaseOrder::create([
                'invoice_number' => $input['invoice_number'],
                'price' => $totalPrice,
            ]);

            foreach ($input['products'] as $inputedProduct) {
                $referencedProduct = $products->where('sku', $inputedProduct['sku'])->first();

                // Calculate the subtotal of the purchase order detail
                $subtotal = $inputedProduct['quantity'] * $inputedProduct['price'];

                // Update the total transaction cost of the purchase order
                $totalPrice += $subtotal;

                // Create a new purchase order detail
                PurchaseOrderDetail::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $referencedProduct->id,
                    'quantity' => $inputedProduct['quantity'],
                    'price' => $inputedProduct['price'],
                    'subtotal' => $subtotal,
                ]);

                // Update the stock of the related product
                $stock = $referencedProduct->stock + $inputedProduct['quantity'];
                $referencedProduct->update(['stock' => $stock]);
            }

            // Store the total transaction cost of the purchase order
            $purchaseOrder->update(['price' => $totalPrice]);
        });

        return Redirect::route('admin.purchase-orders.index');
    }
}
