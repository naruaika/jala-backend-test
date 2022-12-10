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
            'products' => Product::orderBy('sku')->get(),
            'purchaseOrders' => PurchaseOrder::latest()->get(),
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

        $validatedInput = $request->validated();

        // FIXME: optimise these queries to using batch execution
        DB::transaction(function () use ($validatedInput) {
            $totalPrice = 0;

            $purchaseOrder = PurchaseOrder::create([
                'invoice_number' => $validatedInput['invoice_number'],
                'price' => $totalPrice,
            ]);

            foreach ($validatedInput['products'] as $inputedProduct) {
                $product = Product::query()
                    ->where('sku', $inputedProduct['sku'])
                    ->lockForUpdate()
                    ->first();

                $subtotal = $inputedProduct['quantity'] * $inputedProduct['price'];

                PurchaseOrderDetail::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $product->id,
                    'quantity' => $inputedProduct['quantity'],
                    'price' => $inputedProduct['price'],
                    'subtotal' => $subtotal,
                ]);

                $product->update(['stock' => $product->stock + $inputedProduct['quantity']]);

                $totalPrice += $subtotal;
            }

            $purchaseOrder->update(['price' => $totalPrice]);
        });

        return Redirect::route('admin.purchase-orders.index');
    }
}
