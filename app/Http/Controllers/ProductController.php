<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::with('category')
            ->active()
            ->inStock()
            ->search($request->query('q'))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('products.index', [
            'products' => $products,
            'q' => $request->query('q', ''),
        ]);
    }
}
