<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Models\Product;
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
        return Inertia::render('Products/Index', [
            'products' => Product::latest()->get(),
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
        $validatedInput = $request->validated();

        $validatedInput['slug'] = $request->name;

        Product::create($validatedInput);

        return Redirect::route('products.index');
    }
}
