<?php

namespace App\Http\Controllers\Backend;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $data = 'Product Catalog';

        return view('backend.product.index', compact('data'));
    }

    public function productNoVariants()
    {
        $data = 'Product No Variants';

        return view('backend.product.no_variants', compact('data'));
    }

    public function productWithVariants()
    {
        $data = 'Product With Variants';

        return view('backend.product.with_variants', compact('data'));
    }
    
  
}
