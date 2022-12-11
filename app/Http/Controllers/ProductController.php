<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
            'products' => Product::query()
                ->select('sku', 'name', 'price', 'stock')
                ->latest()
                ->get(),
        ]);
    }
}
