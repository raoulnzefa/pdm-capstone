<?php

namespace App\Http\Controllers\Frontend;

use App\Models\CompanyDetails;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductPageController extends Controller
{
    protected $data;

    public function __construct()
    {
        
        $this->data = 'Products';
    }

    public function index(Request $request)
    {
        if (!$request->query('ct'))
        {
    	   $products = Product::where('product_status',1)->paginate(12);
    	   
        }
        else
        {
            $category_id = Category::where('url',$request->query('ct'))->first()->id;
            $products = Product::where('product_status',1)
                ->where('category_id',$category_id)->paginate(12);
        }
        $company = CompanyDetails::first();
        $categories = Category::where('status', 1)->get();

    	return view('frontend.product_page')->with([
            'categories'=>$categories, 
            'products'=>$products, 
            'data'=>$this->data,
            'company' => $company
        ]);
    }

    public function productsByCategory($category)
    {   
        $categories = Category::where('status', 1)->get();
        $categ = Category::where('url', $category)->first();
        $products = Product::where('category_id', $categ->id)->paginate(9);

    	return view('frontend.product_by_category')->with([
            'category'=> $categ,
            'categories'=>$categories,
            'products'=>$products,
            'data'=>$this->data
        ]);
    }

    public function searchProduct(Request $request)
    {
        $search_data = $request->searchProduct;

       return redirect()->route('search.result', ['search_data'=>$search_data, 'data'=> 'Search']);
    }

    public function searchResult(Request $request)
    {
        $search_data = $request->query('search');

        $search_result = Product::where('product_name', 'ILIKE','%'. $search_data .'%')
                        ->get();

        $data = 'Search';
        $company = CompanyDetails::first();
        return view('frontend.search_result_page')->with([
            'data' => $data,
            'search_result'=> $search_result,
            'company' => $company
        ]);
    }
}
