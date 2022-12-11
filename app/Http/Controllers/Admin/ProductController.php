<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCreateRequest;
use App\Models\Product;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        if (! Gate::allows('viewAny', Product::class)) {
            return redirect(RouteServiceProvider::HOME);
        }

        return Inertia::render('Admin/Products/Index', [
            'products' => Product::query()
                ->select('sku', 'name', 'price', 'stock')
                ->latest()
                ->get(),
            'permissions' => [
                'create' => Gate::allows('create', Product::class),
            ],
        ]);
    }

    /**
     * Create a new resource.
     *
     * @param  \App\Http\Requests\ProductCreateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProductCreateRequest $request)
    {
        if (! Gate::allows('create', Product::class)) {
            abort(403);
        }

        $validatedInput = $request->validated();

        $validatedInput['slug'] = $request->name;

        Product::create($validatedInput);

        return Redirect::route('admin.products.index');
    }
}
